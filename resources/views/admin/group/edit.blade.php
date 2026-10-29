@section('title', '編輯角色組')

<x-app-layout>
    <div class="my-6 md:my-9">
        @include('admin.group.tips')

        <div class="mt-5 md:mt-0 md:col-span-2">
            <ul id="tabs" class="flex space-x-2 text-sm">
                <li class="group">
                    <a data-target="basic" href="javascript:void(0)" class="block rounded-t-lg px-3 py-2 bg-white group-hover:bg-white">常規設定</a>
                </li>
                <li class="group">
                    <a data-target="review" href="javascript:void(0)" class="block rounded-t-lg px-3 py-2 bg-gray-200 group-hover:bg-white">圖片審核</a>
                </li>
                <li class="group">
                    <a data-target="protection" href="javascript:void(0)" class="block rounded-t-lg px-3 py-2 bg-gray-200 group-hover:bg-white">原圖保護</a>
                </li>
                <li class="group">
                    <a data-target="watermark" href="javascript:void(0)" class="block rounded-t-lg px-3 py-2 bg-gray-200 group-hover:bg-white">浮水印設定</a>
                </li>
            </ul>
            <form action="{{ route('admin.group.update', ['id' => $group->id]) }}" method="POST">
                <div class="overflow-hidden rounded-md rounded-l-none shadow-custom">
                    <div class="px-4 py-5 bg-white sm:p-6">
                        <div data-tab="basic" class="grid grid-cols-6 gap-6">
                            <div class="col-span-6">
                                <label for="name" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>組名稱</label>
                                <x-input type="text" name="name" id="name" autocomplete="name" placeholder="請輸入組名稱" value="{{ $group->name }}" />
                            </div>

                            <div class="col-span-6">
                                <label for="maximum_file_size" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>最大檔案大小(KB)</label>
                                <x-input type="number" name="configs[maximum_file_size]" id="maximum_file_size" autocomplete="maximum_file_size" placeholder="請輸入上傳檔案的最大限制，單位kb" value="{{ $group->configs->get('maximum_file_size') }}" />
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="concurrent_upload_num" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>併發上傳限制</label>
                                <x-input type="number" name="configs[concurrent_upload_num]" id="concurrent_upload_num" autocomplete="concurrent_upload_num" placeholder="請輸入併發上傳數量" value="{{ $group->configs->get('concurrent_upload_num') }}" />
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="limit_per_minute" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>每分鐘上傳限制</label>
                                <x-input type="number" name="configs[limit_per_minute]" id="limit_per_minute" autocomplete="limit_per_minute" placeholder="請輸入每分鐘可以上傳的圖片數量" value="{{ $group->configs->get('limit_per_minute') }}" />
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="limit_per_hour" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>每小時上傳限制</label>
                                <x-input type="number" name="configs[limit_per_hour]" id="limit_per_hour" autocomplete="limit_per_hour" placeholder="請輸入每小時可以上傳的圖片數量" value="{{ $group->configs->get('limit_per_hour') }}" />
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="limit_per_day" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>每天上傳限制</label>
                                <x-input type="number" name="configs[limit_per_day]" id="limit_per_day" autocomplete="limit_per_day" placeholder="請輸入每天可以上傳的圖片數量" value="{{ $group->configs->get('limit_per_day') }}" />
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="limit_per_week" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>每週上傳限制</label>
                                <x-input type="number" name="configs[limit_per_week]" id="limit_per_week" autocomplete="limit_per_week" placeholder="請輸入每週可以上傳的圖片數量" value="{{ $group->configs->get('limit_per_week') }}" />
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="limit_per_month" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>每月上傳限制</label>
                                <x-input type="number" name="configs[limit_per_month]" id="limit_per_month" autocomplete="limit_per_month" placeholder="請輸入每月可以上傳的圖片數量" value="{{ $group->configs->get('limit_per_month') }}" />
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="path_naming_rule" class="block text-sm font-medium text-gray-700">路徑命名規則</label>
                                <x-input type="text" name="configs[path_naming_rule]" id="path_naming_rule" autocomplete="path_naming_rule" placeholder="請輸入路徑命名規則" value="{{ $group->configs->get('path_naming_rule') }}" />
                                <a href="javascript:void(0)" class="mt-1 text-sm text-emerald-600" id="rename-rules"><i class="fas fa-pencil-alt text-xs"></i> 命名規則對照表</a>
                                @include('admin.group.rules')
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="file_naming_rule" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>檔案命名規則</label>
                                <x-input type="text" name="configs[file_naming_rule]" id="file_naming_rule" autocomplete="file_naming_rule" placeholder="請輸入檔案命名規則" value="{{ $group->configs->get('file_naming_rule') }}" />
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="image_save_quality" class="block text-sm font-medium text-gray-700">圖片儲存品質</label>
                                <x-input type="number" name="configs[image_save_quality]" id="image_save_quality" autocomplete="path_naming_rule" placeholder="請輸入圖片儲存品質" value="{{ $group->configs->get('image_save_quality', 100) }}" />
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="image_save_format" class="block text-sm font-medium text-gray-700">圖片轉換格式</label>
                                <x-select id="configs[image_save_format]" name="configs[image_save_format]" autocomplete="image_save_format">
                                    <option value="">不轉換格式</option>
                                    @foreach($default->get('accepted_file_suffixes') as $extension)
                                        <option value="{{ strtolower($extension) }}" @selected($group->configs->get('image_save_format') === $extension)>{{ strtoupper($extension) }}</option>
                                    @endforeach
                                </x-select>
                            </div>

                            <div class="col-span-6">
                                <x-fieldset title="是否預設" faq="設定預設後，新使用者註冊以後將會屬於該預設角色組，且預設組只能有一個。">
                                    <x-switch id="is_default" name="is_default" value="1" :checked="(bool)$group->is_default"></x-switch>
                                </x-fieldset>
                            </div>

                            <div class="col-span-6">
                                <x-fieldset title="是否為訪客組" faq="設定為訪客組後，未登入使用者受該組控制，且訪客組只能有一個。">
                                    <x-switch id="is_guest" name="is_guest" value="1" :checked="(bool)$group->is_guest"></x-switch>
                                </x-fieldset>
                            </div>

                            <div class="col-span-6">
                                <x-fieldset title="允許上傳的圖片類型">
                                    @foreach($default['accepted_file_suffixes'] as $extension)
                                        <x-fieldset-checkbox id="configs[accepted_file_suffixes]_{{ $extension }}" name="configs[accepted_file_suffixes][]" value="{{ $extension }}" :checked="in_array($extension, $group->configs->get('accepted_file_suffixes'))">
                                            {{ strtoupper($extension) }}
                                        </x-fieldset-checkbox>
                                    @endforeach
                                </x-fieldset>
                            </div>
                        </div>

                        <div data-tab="review" class="hidden grid grid-cols-6 gap-6">
                            <div class="col-span-6 mb-4">
                                <x-fieldset title="圖片審核" faq="設定上傳是否需要應用第三方審查，違規的圖片會被標記為不健康的圖片，或直接被刪除。">
                                    <x-switch id="configs[is_enable_scan]" name="configs[is_enable_scan]" value="1" :checked="(bool)$group->configs->get('is_enable_scan')"></x-switch>
                                </x-fieldset>
                            </div>
                            <div class="col-span-6 mb-4">
                                <x-fieldset title="審核動作">
                                    <x-fieldset-radio id="configs[scanned_action]_mark" name="configs[scanned_action]" value="mark" :checked="$group->configs->get('scanned_action') === 'mark'">標記為不健康</x-fieldset-radio>
                                    <x-fieldset-radio id="configs[scanned_action]_delete" name="configs[scanned_action]" value="delete" :checked="$group->configs->get('scanned_action') === 'delete'">直接刪除</x-fieldset-radio>
                                </x-fieldset>
                            </div>
                            <div class="col-span-6 mb-4">
                                <x-fieldset title="審核驅動">
                                    <x-fieldset-radio id="configs[scan_configs][driver]_tencent" name="configs[scan_configs][driver]" data-select="scan" value="tencent" :checked="($group->configs['scan_configs']['driver'] ?? '') === 'tencent'">騰訊雲</x-fieldset-radio>
                                    <x-fieldset-radio id="configs[scan_configs][driver]_aliyun" name="configs[scan_configs][driver]" data-select="scan" value="aliyun" :checked="($group->configs['scan_configs']['driver'] ?? '') === 'aliyun'">阿里雲</x-fieldset-radio>
                                    <x-fieldset-radio id="configs[scan_configs][driver]_nsfwjs" name="configs[scan_configs][driver]" data-select="scan" value="nsfwjs" :checked="($group->configs['scan_configs']['driver'] ?? '') === 'nsfwjs'">NsfwJs</x-fieldset-radio>
                                </x-fieldset>
                            </div>

                            <div class="hidden mb-4" data-scan-driver="tencent">
                                <div class="col-span-6 sm:col-span-3 mb-4">
                                    <label for="configs[scan_configs][drivers][tencent][endpoint]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>Endpoint</label>
                                    <x-input type="text" name="configs[scan_configs][drivers][tencent][endpoint]" id="configs[scan_configs][drivers][tencent][endpoint]" autocomplete="endpoint" placeholder="請輸入 Endpoint" value="{{ $group->configs['scan_configs']['drivers']['tencent']['endpoint'] ?? '' }}" />
                                </div>
                                <div class="col-span-6 sm:col-span-3 mb-4">
                                    <label for="configs[scan_configs][drivers][tencent][secret_id]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>SecretId</label>
                                    <x-input type="text" name="configs[scan_configs][drivers][tencent][secret_id]" id="configs[scan_configs][drivers][tencent][secret_id]" autocomplete="secret_id" placeholder="請輸入 SecretId" value="{{ $group->configs['scan_configs']['drivers']['tencent']['secret_id'] ?? '' }}" />
                                </div>
                                <div class="col-span-6 sm:col-span-3 mb-4">
                                    <label for="configs[scan_configs][drivers][tencent][secret_key]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>SecretKey</label>
                                    <x-input type="password" name="configs[scan_configs][drivers][tencent][secret_key]" id="configs[scan_configs][drivers][tencent][secret_key]" autocomplete="secret_key" placeholder="請輸入 SecretKey" value="{{ $group->configs['scan_configs']['drivers']['tencent']['secret_key'] ?? '' }}" />
                                </div>
                                <div class="col-span-6 sm:col-span-3 mb-4">
                                    <label for="configs[scan_configs][drivers][tencent][region]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>地域</label>
                                    <x-input type="text" name="configs[scan_configs][drivers][tencent][region]" id="configs[scan_configs][drivers][tencent][region]" autocomplete="region" placeholder="請輸入地域節點，例如：ap-beijing" value="{{ $group->configs['scan_configs']['drivers']['tencent']['region'] ?? '' }}" />
                                </div>
                                <div class="col-span-6 sm:col-span-3 mb-4">
                                    <label for="configs[scan_configs][drivers][tencent][biz_type]" class="block text-sm font-medium text-gray-700">場景名稱</label>
                                    <x-input type="text" name="configs[scan_configs][drivers][tencent][biz_type]" id="configs[scan_configs][drivers][tencent][biz_type]" autocomplete="biz_type" placeholder="業務場景名稱，可為空" value="{{ $group->configs['scan_configs']['drivers']['tencent']['biz_type'] ?? '' }}" />
                                </div>
                            </div>

                            <div class="hidden mb-4" data-scan-driver="aliyun">
                                <div class="col-span-6 sm:col-span-3 mb-4">
                                    <label for="configs[scan_configs][drivers][aliyun][access_key_id]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>AccessKeyId</label>
                                    <x-input type="text" name="configs[scan_configs][drivers][aliyun][access_key_id]" id="configs[scan_configs][drivers][aliyun][access_key_id]" autocomplete="access_key_id" placeholder="請輸入 AccessKeyId" value="{{ $group->configs['scan_configs']['drivers']['aliyun']['access_key_id'] ?? '' }}" />
                                </div>
                                <div class="col-span-6 sm:col-span-3 mb-4">
                                    <label for="configs[scan_configs][drivers][aliyun][access_key_secret]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>AccessKeySecret</label>
                                    <x-input type="password" name="configs[scan_configs][drivers][aliyun][access_key_secret]" id="configs[scan_configs][drivers][aliyun][access_key_secret]" autocomplete="access_key_id" placeholder="請輸入 AccessKeySecret" value="{{ $group->configs['scan_configs']['drivers']['aliyun']['access_key_secret'] ?? '' }}" />
                                </div>
                                <div class="col-span-6 sm:col-span-3 mb-4">
                                    <label for="configs[scan_configs][drivers][aliyun][region_id]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>地域節點</label>
                                    <x-input type="text" name="configs[scan_configs][drivers][aliyun][region_id]" id="configs[scan_configs][drivers][aliyun][region_id]" autocomplete="region_id" placeholder="請輸入地域節點，例如：cn-shanghai" value="{{ $group->configs['scan_configs']['drivers']['aliyun']['region_id'] ?? '' }}" />
                                </div>
                                <div class="col-span-6 sm:col-span-3 mb-4">
                                    <label for="configs[scan_configs][drivers][aliyun][biz_type]" class="block text-sm font-medium text-gray-700">場景名稱</label>
                                    <x-input type="text" name="configs[scan_configs][drivers][aliyun][biz_type]" id="configs[scan_configs][drivers][aliyun][biz_type]" autocomplete="biz_type" placeholder="請輸入業務場景名稱" value="{{ $group->configs['scan_configs']['drivers']['aliyun']['biz_type'] ?? '' }}" />
                                </div>
                                <div class="col-span-6 sm:col-span-3 mb-4">
                                    <x-fieldset title="審核場景">
                                        @foreach($scenes as $key => $scene)
                                            <x-fieldset-checkbox id="configs[scan_configs][drivers][aliyun][scenes][]_{{ $key }}" name="configs[scan_configs][drivers][aliyun][scenes][]" value="{{ $key }}" :checked="in_array($key, ($group->configs['scan_configs']['drivers']['aliyun']['scenes'] ?? []))">{{ $scene }}</x-fieldset-checkbox>
                                        @endforeach
                                    </x-fieldset>
                                </div>
                            </div>

                            <div class="hidden mb-4" data-scan-driver="nsfwjs">
                                <div class="col-span-6 sm:col-span-3 mb-4">
                                    <label for="configs[scan_configs][drivers][nsfwjs][api_url]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>API 位址</label>
                                    <x-input type="url" name="configs[scan_configs][drivers][nsfwjs][api_url]" id="configs[scan_configs][drivers][nsfwjs][api_url]" autocomplete="api_url" placeholder="請輸入API 位址，http(s)://domain.com/classify" value="{{ $group->configs['scan_configs']['drivers']['nsfwjs']['api_url'] ?? '' }}" />
                                </div>
                                <div class="col-span-6 sm:col-span-3 mb-4">
                                    <label for="configs[scan_configs][drivers][nsfwjs][attr_name]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>屬性名稱</label>
                                    <x-input type="text" name="configs[scan_configs][drivers][nsfwjs][attr_name]" id="configs[scan_configs][drivers][nsfwjs][attr_name]" autocomplete="attr_name" placeholder="API的表單檔案屬性名" value="{{ $group->configs['scan_configs']['drivers']['nsfwjs']['attr_name'] ?? '' }}" />
                                </div>
                                <div class="col-span-6 sm:col-span-3 mb-4">
                                    <label for="configs[scan_configs][drivers][nsfwjs][threshold]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>閾值</label>
                                    <x-input type="number" name="configs[scan_configs][drivers][nsfwjs][threshold]" id="configs[scan_configs][drivers][nsfwjs][threshold]" autocomplete="threshold" placeholder="取值 1-100" value="{{ $group->configs['scan_configs']['drivers']['nsfwjs']['threshold'] ?? '' }}" />
                                    <small class="text-gray-500"><i class="fas fa-exclamation-circle"></i> 閾值是指圖片違規程度上限，取值 1-100 之間，數值越低審核越嚴格</small>
                                </div>
                            </div>
                        </div>

                        <div data-tab="protection" class="hidden grid grid-cols-6 gap-6">
                            <div class="col-span-6 mb-4">
                                <x-fieldset title="原圖保護" faq="設定該角色組下的使用者上傳的圖片是否應用原圖保護功能，開啟後圖片<b>不返回直鏈</b>">
                                    <x-switch id="configs[is_enable_original_protection]" name="configs[is_enable_original_protection]" value="1" :checked="(bool)$group->configs->get('is_enable_original_protection')"></x-switch>
                                </x-fieldset>
                            </div>

                            <div class="col-span-6 mb-4">
                                <label for="configs[image_cache_ttl]" class="block text-sm font-medium text-gray-700">圖片快取時間(秒)</label>
                                <x-input type="number" name="configs[image_cache_ttl]" id="configs[image_cache_ttl]" autocomplete="image_cache_ttl" placeholder="請輸入受保護圖片的快取時間，不填或填0表示不快取" value="{{ $group->configs->get('image_cache_ttl') }}" />
                            </div>

                            <a href="javascript:void(0)" id="clear-cache" class="text-sm text-red-500">
                                <i class="fas fa-trash text-xs"></i> <span>清除快取</span>
                            </a>
                        </div>

                        <div data-tab="watermark" class="hidden grid grid-cols-6 gap-6">
                            <p class="mb-3 text-red-600 text-sm"><i class="fas fa-exclamation"></i> 開啟浮水印功能前請注意考慮圖片版權問題。</p>
                            <div class="col-span-6 mb-4">
                                <x-fieldset title="開啟浮水印" faq="請注意，浮水印模式為動態生成時，僅在開啟了「原圖保護」功能的情況下生效。">
                                    <x-switch id="configs[is_enable_watermark]" name="configs[is_enable_watermark]" value="1" :checked="(bool)$group->configs->get('is_enable_watermark')"></x-switch>
                                </x-fieldset>
                                <div class="col-span-6 mt-4 mb-4">
                                    <x-fieldset title="浮水印模式">
                                        <x-fieldset-radio id="configs[watermark_configs][mode]_overlay" name="configs[watermark_configs][mode]" value="{{ \App\Enums\Watermark\Mode::Overlay }}" :checked="($group->configs['watermark_configs']['mode'] ?? '') == \App\Enums\Watermark\Mode::Overlay">覆蓋原圖</x-fieldset-radio>
                                        <x-fieldset-radio id="configs[watermark_configs][mode]_dynamic" name="configs[watermark_configs][mode]" value="{{ \App\Enums\Watermark\Mode::Dynamic }}" :checked="($group->configs['watermark_configs']['mode'] ?? '') == \App\Enums\Watermark\Mode::Dynamic">動態生成</x-fieldset-radio>
                                    </x-fieldset>
                                </div>
                                <div class="col-span-6 mt-4 mb-4">
                                    <x-fieldset title="浮水印類型">
                                        <x-fieldset-radio id="configs[watermark_configs][driver]_font" name="configs[watermark_configs][driver]" data-select="watermark" value="font" :checked="($group->configs['watermark_configs']['driver'] ?? '') === 'font'">文字浮水印</x-fieldset-radio>
                                        <x-fieldset-radio id="configs[watermark_configs][driver]_image" name="configs[watermark_configs][driver]" data-select="watermark" value="image" :checked="($group->configs['watermark_configs']['driver'] ?? '') === 'image'">圖片浮水印</x-fieldset-radio>
                                    </x-fieldset>
                                </div>
                                <div class="mb-4 hidden" data-watermark-driver="font">
                                    <div class="col-span-6 sm:col-span-3 mb-4">
                                        <label for="configs[watermark_configs][drivers][font][font]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>字型檔案</label>
                                        <x-input type="text" name="configs[watermark_configs][drivers][font][font]" id="configs[watermark_configs][drivers][font][font]" autocomplete="text" placeholder="請輸入字型檔案路徑，例如：fonts/lsky.ttf" value="{{ $group->configs['watermark_configs']['drivers']['font']['font'] ?? '' }}" />
                                        <small class="text-yellow-500">請將字型檔案放置 {{ storage_path('app/public') }} 目錄下</small>
                                    </div>
                                    <div class="col-span-6 sm:col-span-3 mb-4">
                                        <label for="configs[watermark_configs][drivers][font][position]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>浮水印位置</label>
                                        <x-select id="configs[watermark_configs][drivers][font][position]" name="configs[watermark_configs][drivers][font][position]" autocomplete="position">
                                            @foreach($positions as $key => $position)
                                                <option value="{{ $key }}" {{ ($group->configs['watermark_configs']['drivers']['font']['position'] ?? '') === $key ? 'selected' : '' }}>{{ $position }}</option>
                                            @endforeach
                                        </x-select>
                                    </div>
                                    <div class="col-span-6 sm:col-span-3 mb-4">
                                        <label for="configs[watermark_configs][drivers][font][text]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>浮水印文字</label>
                                        <x-input type="text" name="configs[watermark_configs][drivers][font][text]" id="configs[watermark_configs][drivers][font][text]" autocomplete="text" placeholder="請輸入浮水印文字" value="{{ $group->configs['watermark_configs']['drivers']['font']['text'] ?? '' }}" />
                                    </div>
                                    <div class="col-span-6 sm:col-span-3 mb-4">
                                        <label for="configs[watermark_configs][drivers][font][color]" class="block text-sm font-medium text-gray-700">字型顏色</label>
                                        <x-input type="text" name="configs[watermark_configs][drivers][font][color]" id="configs[watermark_configs][drivers][font][color]" autocomplete="color" placeholder="請輸入字型顏色，例如：#ffffff" value="{{ $group->configs['watermark_configs']['drivers']['font']['color'] ?? '' }}" />
                                    </div>
                                    <div class="col-span-6 sm:col-span-3 mb-4">
                                        <label for="configs[watermark_configs][drivers][font][size]" class="block text-sm font-medium text-gray-700">字型大小</label>
                                        <x-input type="number" name="configs[watermark_configs][drivers][font][size]" id="configs[watermark_configs][drivers][font][size]" autocomplete="size" placeholder="請輸入字型大小，預設 14" value="{{ $group->configs['watermark_configs']['drivers']['font']['size'] ?? '' }}" />
                                    </div>
                                    <div class="col-span-6 sm:col-span-3 mb-4">
                                        <label for="configs[watermark_configs][drivers][font][angle]" class="block text-sm font-medium text-gray-700">旋轉角度</label>
                                        <x-input type="number" name="configs[watermark_configs][drivers][font][angle]" id="configs[watermark_configs][drivers][font][angle]" autocomplete="angle" placeholder="請輸入旋轉角度，預設 0，可以為" value="{{ $group->configs['watermark_configs']['drivers']['font']['angle'] ?? '' }}" />
                                    </div>
                                    <div class="col-span-6 sm:col-span-3 mb-4">
                                        <label for="configs[watermark_configs][drivers][font][x]" class="block text-sm font-medium text-gray-700">X軸偏移量</label>
                                        <x-input type="number" name="configs[watermark_configs][drivers][font][x]" id="configs[watermark_configs][drivers][font][x]" autocomplete="x" placeholder="X軸偏移量" value="{{ $group->configs['watermark_configs']['drivers']['font']['x'] ?? '' }}" />
                                    </div>
                                    <div class="col-span-6 sm:col-span-3 mb-4">
                                        <label for="configs[watermark_configs][drivers][font][y]" class="block text-sm font-medium text-gray-700">Y軸偏移量</label>
                                        <x-input type="number" name="configs[watermark_configs][drivers][font][y]" id="configs[watermark_configs][drivers][font][y]" autocomplete="y" placeholder="Y軸偏移量" value="{{ $group->configs['watermark_configs']['drivers']['font']['y'] ?? '' }}" />
                                    </div>
                                </div>
                                <div class="mb-4 hidden" data-watermark-driver="image">
                                    <div class="col-span-6 sm:col-span-3 mb-4">
                                        <label for="configs[watermark_configs][drivers][image][image]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>浮水印圖片</label>
                                        <x-input type="text" name="configs[watermark_configs][drivers][image][image]" id="configs[watermark_configs][drivers][image][image]" autocomplete="image" placeholder="請輸入浮水印路徑，例如：images/lsky.png" value="{{ $group->configs['watermark_configs']['drivers']['image']['image'] ?? '' }}" />
                                        <small class="text-yellow-500">請將浮水印圖片放置 {{ storage_path('app/public') }} 目錄下</small>
                                    </div>
                                    <div class="col-span-6 sm:col-span-3 mb-4">
                                        <label for="configs[watermark_configs][drivers][image][position]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>浮水印位置</label>
                                        <x-select id="configs[watermark_configs][drivers][image][position]" name="configs[watermark_configs][drivers][image][position]" autocomplete="position">
                                            @foreach($positions as $key => $position)
                                                <option value="{{ $key }}" {{ ($group->configs['watermark_configs']['drivers']['image']['position'] ?? '') === $key ? 'selected' : '' }}>{{ $position }}</option>
                                            @endforeach
                                        </x-select>
                                    </div>
                                    <div class="col-span-6 sm:col-span-3 mb-4">
                                        <label for="configs[watermark_configs][drivers][image][width]" class="block text-sm font-medium text-gray-700">圖片寬度</label>
                                        <x-input type="number" name="configs[watermark_configs][drivers][image][width]" id="configs[watermark_configs][drivers][image][width]" autocomplete="width" placeholder="請輸入浮水印圖片寬度" value="{{ $group->configs['watermark_configs']['drivers']['image']['width'] ?? '' }}" />
                                    </div>
                                    <div class="col-span-6 sm:col-span-3 mb-4">
                                        <label for="configs[watermark_configs][drivers][image][height]" class="block text-sm font-medium text-gray-700">圖片高度</label>
                                        <x-input type="number" name="configs[watermark_configs][drivers][image][height]" id="configs[watermark_configs][drivers][image][height]" autocomplete="height" placeholder="請輸入浮水印圖片高度" value="{{ $group->configs['watermark_configs']['drivers']['image']['height'] ?? '' }}" />
                                    </div>
                                    <div class="col-span-6 sm:col-span-3 mb-4">
                                        <label for="configs[watermark_configs][drivers][image][opacity]" class="block text-sm font-medium text-gray-700">不透明度</label>
                                        <x-input type="number" name="configs[watermark_configs][drivers][image][opacity]" id="configs[watermark_configs][drivers][image][opacity]" autocomplete="opacity" placeholder="請輸入不透明度，取值 0 - 100" value="{{ $group->configs['watermark_configs']['drivers']['image']['opacity'] ?? '' }}" />
                                    </div>
                                    <div class="col-span-6 sm:col-span-3 mb-4">
                                        <label for="configs[watermark_configs][drivers][image][rotate]" class="block text-sm font-medium text-gray-700">旋轉角度</label>
                                        <x-input type="number" name="configs[watermark_configs][drivers][image][rotate]" id="configs[watermark_configs][drivers][image][rotate]" autocomplete="rotate" placeholder="請輸入旋轉角度，預設 0" value="{{ $group->configs['watermark_configs']['drivers']['image']['rotate'] ?? '' }}" />
                                    </div>
                                    <div class="col-span-6 sm:col-span-3 mb-4">
                                        <label for="configs[watermark_configs][drivers][image][x]" class="block text-sm font-medium text-gray-700">X軸偏移量</label>
                                        <x-input type="number" name="configs[watermark_configs][drivers][image][x]" id="configs[watermark_configs][drivers][image][x]" autocomplete="x" placeholder="X軸偏移量" value="{{ $group->configs['watermark_configs']['drivers']['image']['x'] ?? '' }}" />
                                    </div>
                                    <div class="col-span-6 sm:col-span-3 mb-4">
                                        <label for="configs[watermark_configs][drivers][image][y]" class="block text-sm font-medium text-gray-700">Y軸偏移量</label>
                                        <x-input type="number" name="configs[watermark_configs][drivers][image][y]" id="configs[watermark_configs][drivers][image][y]" autocomplete="y" placeholder="Y軸偏移量" value="{{ $group->configs['watermark_configs']['drivers']['image']['y'] ?? '' }}" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <x-button type="button" class="bg-gray-500" onclick="history.go(-1)">取消</x-button>
                        <x-button>確認儲存</x-button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // tabs select
            $('[data-target]').click(function () {
                $('[data-tab]').hide();
                $('[data-target]').removeClass('bg-white').addClass('bg-gray-200');
                $(`[data-tab=${$(this).data('target')}]`).show();
                $(this).removeClass('bg-gray-200').addClass('bg-white');
            });

            // 設定選取驅動
            let setSelected = function () {
                $('[data-select]').each(function () {
                    $(`[data-${$(this).data('select')}-driver=${$(this).val()}]`)[this.checked ? 'show' : 'hide']();
                });
            };

            setSelected();

            $('[data-select]').click(function () {
                setSelected();
            });

            $('form').submit(function (e) {
                e.preventDefault();
                axios.put(this.action, $(this).serialize()).then(response => {
                    if (response.data.status) {
                        toastr.success(response.data.message);
                    } else {
                        toastr.error(response.data.message);
                    }
                });
            });

            $('#clear-cache').click(function () {
                if ($(this).hasClass('text-red-100')) return;

                $(this).removeClass('text-red-400').addClass('text-red-300').find('span').text('清除中...');
                axios.delete('{{ route('admin.group.cache.clear', ['id' => $group->id]) }}').then(response => {
                    $(this).removeClass('text-red-300').addClass('text-red-400').find('span').text('清除快取');
                    if (response.data.status) {
                        toastr.success(response.data.message);
                    } else {
                        toastr.error(response.data.message);
                    }
                });
            });
        </script>
    @endpush

</x-app-layout>
