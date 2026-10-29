<?php

namespace App\Services;

use App\Models\Image;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class UserService
{
    /**
     * 刪除圖片以及物理檔案，返回已刪除數量
     * 考慮到刪除磁碟檔案、伺服器內部請求第三方刪除API比較消耗資源，傳入的主鍵不應過多
     * TODO 改進迴圈中更新資料
     *
     * @param array $keys
     * @param User|null $user 傳入使用者資料則會根據使用者id過濾
     * @param string $field
     * @return int
     */
    public function deleteImages(array $keys, ?User $user = null, string $field = 'id'): int
    {
        $count = 0;
        $model = Image::with('user', 'strategy', 'album')->when(! is_null($user), function (Builder $builder) use ($user) {
            $builder->where('user_id', $user->id);
        })->whereIn($field, $keys);

        DB::transaction(function () use ($model, $keys, &$count) {
            /** @var Image $image */
            foreach ($model->cursor() as $image) {
                // 相簿圖片數量更新
                $image->album?->decrement('image_num');
                // 更新相簿圖片數量
                $image->delete();
                // 更新數量
                if ($image->user) {
                    $image->user->image_num = $image->user->images()->count();
                    $image->user->save();
                }

                $count++;
            }
        });

        return $count;
    }
}
