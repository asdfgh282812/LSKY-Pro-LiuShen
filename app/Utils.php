<?php

namespace App;

use App\Enums\ConfigKey;
use App\Models\Config;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class Utils
{
    public static function e(\Throwable $e, $message = '', $level = 'error')
    {
        Log::{$level}($message, [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
    }

    /**
     * 取得頭像位址
     *
     * @param $email
     * @param int $s
     * @param string $d
     * @param string $r
     * @return string
     */
    public static function getAvatar($email, int $s = 96, string $d = 'mp', string $r = 'g'): string
    {
        $mirror = trim((string) Utils::config(ConfigKey::AvatarMirrorUrl, ''));
        $url = ($mirror === '' ? 'https://weavatar.com/avatar/' : rtrim($mirror, '/').'/');
        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$s&d=$d&r=$r";
        return $url;
    }

    /**
     * 取得系統設定，取得全部設定時將返回
     *
     * @param  string  $name
     * @param  mixed|null  $default
     *
     * @return mixed
     */
    public static function config(string $name = '', mixed $default = null): mixed
    {
        /** @var Collection $configs */
        $configs = Cache::rememberForever('configs', function () {
            return Config::query()->pluck('value', 'name')->transform(function ($value, $key) {
                switch ($key) {
                    case ConfigKey::IsAllowGuestUpload:
                    case ConfigKey::IsEnableGallery:
                    case ConfigKey::IsEnableApi:
                    case ConfigKey::IsEnableRegistration:
                    case ConfigKey::IsUserNeedVerify:
                    case ConfigKey::OauthEnable:
                    case ConfigKey::OauthForceLogin:
                    case ConfigKey::OauthAllowRegister:
                    case ConfigKey::OauthPkceEnable:
                        $value = (bool) $value;
                        break;
                    case ConfigKey::OauthClientSecret:
                        if (is_string($value) && $value !== '') {
                            try {
                                $value = decrypt($value);
                            } catch (\Throwable) {
                            }
                        }
                        break;
                    case ConfigKey::Mail:
                    case ConfigKey::Group:
                        $value = collect(json_decode($value, true));
                        break;
                    case ConfigKey::UserInitialCapacity:
                        $value = sprintf('%.2f', $value);
                        break;
                    default:
                }
                return $value;
            });
        });
        return '' === $name ? $configs : $configs->get($name, $default);
    }

    /**
     * 生成連續日期.
     * @param  string  $start  開始日期
     * @param  string  $end  結束日期
     * @param  string  $unit  day=日，month=月，year=年
     * @return array
     */
    public static function makeDateRange(string $start, string $end, string $unit = 'day'): array
    {
        $array = [];
        $format = ['day' => 'Y-m-d', 'month' => 'Y-m', 'year' => 'Y'][$unit] ?? 'Y-m-d';
        Carbon::create($start)->range($end, 1, $unit)->forEach(function (Carbon $item) use (&$array, $format) {
            $array[] = $item->format($format);
        });
        return $array;
    }

    /**
     * 轉換欄位單位
     *
     * @param  int|float  $size  位元組b
     * @return string
     */
    public static function formatSize(int|float $size): string
    {
        if ($size <= 0) {
            return "0.00 Bytes";
        }
        $unit = ['', 'K', 'M', 'G', 'T', 'P'];
        $base = 1024;
        $i = floor(log($size, $base));
        $n = count($unit);
        if ($i >= $n) {
            $i = $n - 1;
        }

        return sprintf("%.2f", $size / pow($base, $i)).' '.$unit[$i].'B';
    }

    /**
     * 格式化數字
     *
     * @param int|string $n 數字
     * @param int $precision 精度
     * @return int|string
     */
    public static function shortenNumber(int|string $n, int $precision = 1): int|string
    {
        if ($n < 1e+3) {
            return number_format($n);
        } else if ($n < 1e+6) {
            return number_format($n / 1e+3, $precision) . 'k';
        } else if ($n < 1e+9) {
            return number_format($n / 1e+6, $precision) . 'm';
        } else if ($n < 1e+12) {
            return number_format($n / 1e+9, $precision) . 'b';
        }

        return $n;
    }

    /**
     * 遞迴過濾陣列元素
     *
     * @param  array  $array
     * @param  callable|null  $callback
     * @param  int  $mode
     * @return array
     */
    public static function filter(array $array, ?callable $callback = null, int $mode = 0): array
    {
        foreach ($array as &$value) {
            if (is_array($value)) {
                $value = self::filter($value, $callback, $mode);
            }
        }
        return array_filter($array, $callback, $mode);
    }

    /**
     * 格式化設定，設定預設設定以及將字串數字轉換為數字
     *
     * @param  array  $defaults  預設設定
     * @param  array  $configs  新設定
     * @return array
     */
    public static function parseConfigs(array $defaults, array $configs): array
    {
        array_walk_recursive($configs, function (&$item) {
            if (ctype_digit($item)) {
                $item += 0;
            }
            if (is_null($item)) {
                unset($item);
            }
        });
        return self::array_merge_recursive_distinct($defaults, $configs);
    }

    /**
     * @param array<int|string, mixed> $array1
     * @param array<int|string, mixed> $array2
     *
     * @return array<int|string, mixed>
     */
    private static function array_merge_recursive_distinct(array $array1, array &$array2): array
    {
        $merged = $array1;
        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key]) && ! array_is_list($value)) {
                $merged[$key] = self::array_merge_recursive_distinct($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
}
