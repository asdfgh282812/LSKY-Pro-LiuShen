@section('title', '建立儲存策略')

<x-app-layout>
    <div class="my-6 md:my-9">
        @include('admin.strategy.tips')

        <div class="md:mt-0 md:col-span-2">
            <form action="{{ route('admin.strategy.create') }}" method="POST">
                <div class="overflow-hidden rounded-md">
                    <div class="px-4 py-5 bg-white sm:p-6 space-y-4 shadow-custom">
                        <div class="col-span-6">
                            <label class="block">
                                <span class="text-gray-700">選擇角色組</span>
                                <x-select name="groups[]" class="block w-full mt-1 form-multiselect" multiple>
                                    @foreach(\App\Models\Group::query()->get() as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                    @endforeach
                                </x-select>
                            </label>
                        </div>

                        <div class="col-span-6">
                            <label for="name" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>名稱</label>
                            <x-input type="text" name="name" id="name" placeholder="請輸入策略名稱" autocomplete="name" />
                        </div>

                        <div class="col-span-6">
                            <label for="intro" class="block text-sm font-medium text-gray-700">簡介</label>
                            <x-textarea id="intro" name="intro" rows="3" placeholder="請輸入簡介，可為空"></x-textarea>
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="key" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>儲存策略</label>
                            <x-select id="key" name="key" autocomplete="key" required>
                                @foreach(\App\Models\Strategy::DRIVERS as $key => $driver)
                                <option value="{{ $key }}" {{ $loop->first ? 'selected' : '' }}>{{ $driver }}</option>
                                @endforeach
                            </x-select>
                        </div>

                        <div class="col-span-6 mb-4 hidden" data-driver="{{ \App\Enums\StrategyKey::Local }}">
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[url]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>存取網址</label>
                                <x-input type="text" name="configs[url]" id="configs[url]" placeholder="請輸入圖片存取網域，需要加 http(s)://" />
                                <small class="text-orange-500"><i class="fas fa-exclamation"></i>
                                    本地儲存的存取網址必須有根路徑，例如：https://www.lsky.pro/uploads 中的 uploads 就是根路徑，且根路徑不能和其他策略重複。修改根路徑直接影響已經上傳並已使用的連結的存取。
                                </small>
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[queries]" class="block text-sm font-medium text-gray-700">URL Queries</label>
                                <x-input type="text" name="configs[queries]" id="configs[queries]" placeholder="請輸入 url 額外參數" />
                            </div>
                            <div class="col-span-6">
                                <div class="col-span-6 sm:col-span-3 mb-4">
                                    <label for="configs[root]" class="block text-sm font-medium text-gray-700">儲存路徑</label>
                                    <x-input type="text" name="configs[root]" id="configs[root]" autocomplete="text" placeholder="圖片儲存位置，預設：{{ config('filesystems.disks.uploads.root') }}" />
                                    <small class="text-orange-500"><i class="fas fa-exclamation"></i> 儲存路徑為絕對路徑，設定錯誤或沒有讀寫權限可能會導致圖片儲存失敗。如果儲存路徑與其他策略相同，那麼請注意使用角色組的路徑命名規則、檔案命名規則來區分不同資料夾，否則可能會因為名稱重複而導致圖片物理檔案被覆蓋。</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-span-6 mb-4 hidden" data-driver="{{ \App\Enums\StrategyKey::S3 }}">
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[url]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>存取網域</label>
                                <x-input type="url" name="configs[url]" id="configs[url]" placeholder="請輸入圖片存取網域 http(s)://" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[queries]" class="block text-sm font-medium text-gray-700">URL Queries</label>
                                <x-input type="text" name="configs[queries]" id="configs[queries]" placeholder="請輸入 url 額外參數" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[access_key_id]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>AccessKeyId</label>
                                <x-input type="text" name="configs[access_key_id]" id="configs[access_key_id]" placeholder="請輸入 AccessKeyId" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[secret_access_key]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>SecretAccessKey</label>
                                <x-input type="password" name="configs[secret_access_key]" id="configs[secret_access_key]" placeholder="請輸入 SecretAccessKey" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[endpoint]" class="block text-sm font-medium text-gray-700">連線位址</label>
                                <x-input type="url" name="configs[endpoint]" id="configs[endpoint]" placeholder="請輸入連線位址" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[region]" class="block text-sm font-medium text-gray-700">區域(region)</label>
                                <x-input type="text" name="configs[region]" id="configs[region]" placeholder="請輸入區域，例如：us-east-1" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[bucket]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>儲存桶名稱</label>
                                <x-input type="text" name="configs[bucket]" id="configs[bucket]" placeholder="請輸入儲存桶名稱" />
                            </div>
                        </div>

                        <div class="col-span-6 mb-4 hidden" data-driver="{{ \App\Enums\StrategyKey::Oss }}">
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[url]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>存取網域</label>
                                <x-input type="url" name="configs[url]" id="configs[url]" placeholder="請輸入圖片存取網域 http(s)://" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[queries]" class="block text-sm font-medium text-gray-700">URL Queries</label>
                                <x-input type="text" name="configs[queries]" id="configs[queries]" placeholder="請輸入 url 額外參數" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[access_key_id]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>AccessKeyId</label>
                                <x-input type="text" name="configs[access_key_id]" id="configs[access_key_id]" placeholder="請輸入 AccessKeyId" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[access_key_secret]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>AccessKeySecret</label>
                                <x-input type="password" name="configs[access_key_secret]" id="configs[access_key_secret]" placeholder="請輸入 AccessKeySecret" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[endpoint]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>地域節點(Endpoint)</label>
                                <x-input type="text" name="configs[endpoint]" id="configs[endpoint]" placeholder="請輸入所屬地域節點，例如：oss-cn-beijing.aliyuncs.com" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[bucket]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>Bucket 名稱</label>
                                <x-input type="text" name="configs[bucket]" id="configs[bucket]" placeholder="請輸入 Bucket 名稱" />
                            </div>
                        </div>

                        <div class="col-span-6 mb-4 hidden" data-driver="{{ \App\Enums\StrategyKey::Cos }}">
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[url]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>存取網域</label>
                                <x-input type="url" name="configs[url]" id="configs[url]" placeholder="請輸入圖片存取網域 http(s)://" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[queries]" class="block text-sm font-medium text-gray-700">URL Queries</label>
                                <x-input type="text" name="configs[queries]" id="configs[queries]" placeholder="請輸入 url 額外參數" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[app_id]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>AppId</label>
                                <x-input type="text" name="configs[app_id]" id="configs[app_id]" placeholder="請輸入 AppId" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[secret_id]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>SecretId</label>
                                <x-input type="text" name="configs[secret_id]" id="configs[secret_id]" placeholder="請輸入 SecretId" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[secret_key]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>SecretKey</label>
                                <x-input type="password" name="configs[secret_key]" id="configs[secret_key]" placeholder="請輸入 SecretKey" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[region]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>所屬地域</label>
                                <x-input type="text" name="configs[region]" id="configs[region]" placeholder="請輸入所屬地域，例如：ap-chengdu" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[bucket]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>儲存桶名稱</label>
                                <x-input type="text" name="configs[bucket]" id="configs[bucket]" placeholder="請輸入儲存桶名稱" />
                                <small class="text-gray-500"><i class="fas fa-exclamation-circle"></i> 騰訊雲儲存桶名稱由 名稱+appid 組合，例如：test-125146xxxx，此處應該填寫 test</small>
                            </div>
                        </div>

                        <div class="col-span-6 mb-4 hidden" data-driver="{{ \App\Enums\StrategyKey::Kodo }}">
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[url]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>存取網域</label>
                                <x-input type="url" name="configs[url]" id="configs[url]" placeholder="請輸入圖片存取網域 http(s)://" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[queries]" class="block text-sm font-medium text-gray-700">URL Queries</label>
                                <x-input type="text" name="configs[queries]" id="configs[queries]" placeholder="請輸入 url 額外參數" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[access_key]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>AccessKey</label>
                                <x-input type="text" name="configs[access_key]" id="configs[access_key]" placeholder="請輸入 AccessKey" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[secret_key]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>SecretKey</label>
                                <x-input type="password" name="configs[secret_key]" id="configs[secret_key]" placeholder="請輸入 SecretKey" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[bucket]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>Bucket</label>
                                <x-input type="text" name="configs[bucket]" id="configs[bucket]" placeholder="請輸入 Bucket" />
                            </div>
                        </div>

                        <div class="col-span-6 mb-4 hidden" data-driver="{{ \App\Enums\StrategyKey::Uss }}">
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[url]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>存取網域</label>
                                <x-input type="url" name="configs[url]" id="configs[url]" placeholder="請輸入圖片存取網域 http(s)://" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[queries]" class="block text-sm font-medium text-gray-700">URL Queries</label>
                                <x-input type="text" name="configs[queries]" id="configs[queries]" placeholder="請輸入 url 額外參數" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[service]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>服務名稱</label>
                                <x-input type="text" name="configs[service]" id="configs[service]" placeholder="請輸入服務名稱" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[operator]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>操作員名稱</label>
                                <x-input type="text" name="configs[operator]" id="configs[operator]" placeholder="請輸入操作員名稱" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[password]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>操作員密碼</label>
                                <x-input type="password" name="configs[password]" id="configs[password]" placeholder="請輸入操作員密碼" />
                            </div>
                        </div>

                        <div class="col-span-6 mb-4 hidden" data-driver="{{ \App\Enums\StrategyKey::Sftp }}">
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[url]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>存取網域</label>
                                <x-input type="url" name="configs[url]" id="configs[url]" placeholder="請輸入圖片存取網域 http(s)://" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[queries]" class="block text-sm font-medium text-gray-700">URL Queries</label>
                                <x-input type="text" name="configs[queries]" id="configs[queries]" placeholder="請輸入 url 額外參數" />
                            </div>
                            <div class="col-span-6 sm:col-span-3 mb-4">
                                <label for="configs[root]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>根目錄</label>
                                <x-input type="text" name="configs[root]" id="configs[root]" autocomplete="text" placeholder="請輸入根目錄路徑" value="/" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[host]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>主機位址</label>
                                <x-input type="text" name="configs[host]" id="configs[host]" placeholder="請輸入主機位址，例如：127.0.0.1" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[port]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>連線連接埠</label>
                                <x-input type="number" name="configs[port]" id="configs[port]" placeholder="請輸入連線連接埠" value="22" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[username]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>使用者名稱</label>
                                <x-input type="text" name="configs[username]" id="configs[username]" placeholder="請輸入使用者名稱" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[password]" class="block text-sm font-medium text-gray-700"><span class="text-yellow-500">*</span>密碼</label>
                                <x-input type="password" name="configs[password]" id="configs[password]" placeholder="如果使用私鑰連線，可為空" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[private_key]" class="block text-sm font-medium text-gray-700"><span class="text-yellow-500">*</span>私鑰</label>
                                <x-textarea name="configs[private_key]" id="configs[private_key]" placeholder="輸入私鑰文字內容，如果使用密碼連線，可為空"></x-textarea>
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[passphrase]" class="block text-sm font-medium text-gray-700">私鑰口令</label>
                                <x-input type="password" name="configs[passphrase]" id="configs[passphrase]" placeholder="如果未設定私鑰或私鑰未設定口令，可為空" />
                            </div>
                            <div class="col-span-6">
                                <label for="configs[use_agent]" class="block text-sm font-medium mb-2 text-gray-700">是否使用代理</label>
                                <x-switch id="configs[use_agent]" name="configs[use_agent]" value="1"></x-switch>
                            </div>
                        </div>

                        <div class="col-span-6 mb-4 hidden" data-driver="{{ \App\Enums\StrategyKey::Ftp }}">
                            @if(! extension_loaded('ftp'))
                                <p class="bg-yellow-500 p-2 mb-4 rounded text-sm text-white">
                                    <i class="fas fa-exclamation-circle"></i> 系統檢測到 ftp 擴充未啟用，可能無法正常使用 FTP 儲存方式。
                                </p>
                            @endif
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[url]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>存取網域</label>
                                <x-input type="url" name="configs[url]" id="configs[url]" placeholder="請輸入圖片存取網域 http(s)://" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[queries]" class="block text-sm font-medium text-gray-700">URL Queries</label>
                                <x-input type="text" name="configs[queries]" id="configs[queries]" placeholder="請輸入 url 額外參數" />
                            </div>
                            <div class="col-span-6 sm:col-span-3 mb-4">
                                <label for="configs[root]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>根目錄</label>
                                <x-input type="text" name="configs[root]" id="configs[root]" autocomplete="text" placeholder="請輸入根目錄路徑" value="/" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[host]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>主機位址</label>
                                <x-input type="text" name="configs[host]" id="configs[host]" placeholder="請輸入主機位址，例如：127.0.0.1" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[port]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>連線連接埠</label>
                                <x-input type="number" name="configs[port]" id="configs[port]" placeholder="請輸入連線連接埠 21/20" value="20" />
                                <small class="text-gray-500"><i class="fas fa-exclamation-circle"></i> 通常情況下，FTP 的被動模式連線連接埠為 21，主動模式為 20</small>
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[username]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>使用者名稱</label>
                                <x-input type="text" name="configs[username]" id="configs[username]" placeholder="請輸入使用者名稱" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[password]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>密碼</label>
                                <x-input type="password" name="configs[password]" id="configs[password]" placeholder="請輸入密碼" />
                            </div>
                            <div class="col-span-6">
                                <label for="configs[ssl]" class="block text-sm font-medium mb-2 text-gray-700">加密連線</label>
                                <x-switch id="configs[ssl]" name="configs[ssl]" value="1"></x-switch>
                            </div>
                            <div class="col-span-6">
                                <label for="configs[passive]" class="block text-sm font-medium my-2 text-gray-700">被動模式</label>
                                <x-switch id="configs[passive]" name="configs[passive]" value="1"></x-switch>
                            </div>
                        </div>

                        <div class="col-span-6 mb-4 hidden" data-driver="{{ \App\Enums\StrategyKey::Webdav }}">
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[url]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>存取網域</label>
                                <x-input type="url" name="configs[url]" id="configs[url]" placeholder="請輸入圖片存取網域 http(s)://" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[queries]" class="block text-sm font-medium text-gray-700">URL Queries</label>
                                <x-input type="text" name="configs[queries]" id="configs[queries]" placeholder="請輸入 url 額外參數" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[base_uri]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>連線位址</label>
                                <x-input type="url" name="configs[base_uri]" id="configs[base_uri]" placeholder="請輸入連線位址" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="webdav-configs[auth_type]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>認證方式</label>
                                <x-select id="webdav-auth-type" name="configs[auth_type]" select2>
                                    @foreach(\App\Models\Strategy::WEBDAV_AUTH_TYPES as $key => $type)
                                        <option value="{{ $key }}" {{ $loop->first ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </x-select>
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="webdav-configs[prefix]" class="block text-sm font-medium text-gray-700">路徑字首</label>
                                <x-input type="text" name="configs[prefix]" id="webdav-configs[prefix]" placeholder="請輸入路徑字首"></x-input>
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[username]" class="block text-sm font-medium text-gray-700">使用者名稱</label>
                                <x-input type="text" name="configs[username]" id="configs[username]" placeholder="請輸入使用者名稱" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[password]" class="block text-sm font-medium text-gray-700">密碼</label>
                                <x-input type="password" name="configs[password]" id="configs[password]" placeholder="請輸入密碼" />
                            </div>
                        </div>

                        <div class="col-span-6 mb-4 hidden" data-driver="{{ \App\Enums\StrategyKey::Minio }}">
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[url]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>存取網域</label>
                                <x-input type="url" name="configs[url]" id="configs[url]" placeholder="請輸入圖片存取網域 http(s)://" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[queries]" class="block text-sm font-medium text-gray-700">URL Queries</label>
                                <x-input type="text" name="configs[queries]" id="configs[queries]" placeholder="請輸入 url 額外參數" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[access_key]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>AccessKey</label>
                                <x-input type="text" name="configs[access_key]" id="configs[access_key]" placeholder="請輸入 AccessKey" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[secret_key]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>SecretKey</label>
                                <x-input type="password" name="configs[secret_key]" id="configs[secret_key]" placeholder="請輸入 SecretKey" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[endpoint]" class="block text-sm font-medium text-gray-700">連線位址</label>
                                <x-input type="url" name="configs[endpoint]" id="configs[endpoint]" placeholder="請輸入連線位址" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[region]" class="block text-sm font-medium text-gray-700">區域(region)</label>
                                <x-input type="text" name="configs[region]" id="configs[region]" placeholder="請輸入區域，例如：us-east-1" />
                            </div>
                            <div class="col-span-3 sm:col-span-2 mb-4">
                                <label for="configs[bucket]" class="block text-sm font-medium text-gray-700"><span class="text-red-600">*</span>Bucket 名稱</label>
                                <x-input type="text" name="configs[bucket]" id="configs[bucket]" placeholder="請輸入 Bucket 名稱" />
                            </div>
                            <div class="col-span-6">
                                <label for="configs[bucket_endpoint]" class="block text-sm font-medium mb-2 text-gray-700">BucketEndpoint</label>
                                <x-switch id="configs[bucket_endpoint]" name="configs[bucket_endpoint]" value="1"></x-switch>
                                <p><small class="text-gray-500"><i class="fas fa-exclamation-circle"></i> 開啟此選項後將會直接以「連線位址」作為資料互動傳輸網域名稱，否則可能會以桶名稱拼接網域名稱(例如：http://桶名稱.連線位址.com)</small></p>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <x-button type="button" class="bg-gray-500" onclick="history.go(-1)">取消</x-button>
                        <x-button>確認建立</x-button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // 設定選取驅動
            let setSelected = function () {
                $('[data-driver]').each(function () {
                    if ($('#key').val() == $(this).data('driver')) {
                        $(this).show().find('input').attr('disabled', false);
                    } else {
                        $(this).hide().find('input').attr('disabled', true);
                    }
                });
            };

            setSelected();

            $('#key').change(function () {
                setSelected();
            });

            $('form').submit(function (e) {
                e.preventDefault();
                axios.post(this.action, $(this).serialize()).then(response => {
                    if (response.data.status) {
                        toastr.success(response.data.message);
                        setTimeout(function () {
                            window.location.href = '{{ route('admin.strategies') }}';
                        }, 3000);
                    } else {
                        toastr.error(response.data.message);
                    }
                });
            });
        </script>
    @endpush

</x-app-layout>
