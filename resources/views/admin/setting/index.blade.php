@section('title', '系統設定')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/markdown-css/github-markdown-light.css') }}">
@endpush

<x-app-layout>
    <div class="my-6 md:my-9">
        <p class="admin-section-title">通用</p>
        <form action="{{ route('admin.settings.save') }}">
            <div class="admin-section space-y-4">
                <div>
                    <label for="app_name" class="block text-sm font-medium text-slate-700"><span class="text-red-600">*</span>應用名稱</label>
                    <x-input type="text" name="app_name" id="app_name" value="{{ $configs->get('app_name') }}" placeholder="請輸入應用名稱"/>
                </div>
                <div>
                    <label for="site_keywords" class="block text-sm font-medium text-slate-700">網站關鍵字</label>
                    <x-textarea type="text" name="site_keywords" id="site_keywords" placeholder="請輸入網站關鍵字">{{ $configs->get('site_keywords') }}</x-textarea>
                </div>
                <div>
                    <label for="site_description" class="block text-sm font-medium text-slate-700">網站描述</label>
                    <x-textarea type="text" name="site_description" id="site_description" placeholder="請輸入網站描述">{{ $configs->get('site_description') }}</x-textarea>
                </div>
                <div>
                    <label for="icp_no" class="block text-sm font-medium text-slate-700">備案號</label>
                    <x-input type="text" name="icp_no" id="icp_no" value="{{ $configs->get('icp_no') }}" placeholder="請輸入備案號"/>
                </div>
                <div>
                    <label for="avatar_mirror_url" class="block text-sm font-medium text-slate-700">頭像映象位址</label>
                    <p class="text-xs text-slate-400 mb-1.5">基於電子郵件 MD5 的 Gravatar 相容頭像服務位址，末尾斜槓可寫可不寫，留空使用預設 weavatar.com</p>
                    <x-input type="url" name="avatar_mirror_url" id="avatar_mirror_url" value="{{ $configs->get('avatar_mirror_url') }}" placeholder="https://weavatar.com/avatar/"/>
                </div>
                <div>
                    <label for="site_notice" class="block text-sm font-medium text-slate-700">網站公告</label>
                    <x-textarea type="text" name="site_notice" id="site_notice" placeholder="首頁彈出公告，支援Markdown，不設定請留空" rows="7">{{ $configs->get('site_notice') }}</x-textarea>
                </div>

                <div class="text-right">
                    <x-button type="submit">儲存更改</x-button>
                </div>
            </div>
        </form>

        <p class="admin-section-title">控制</p>
        <form action="{{ route('admin.settings.save') }}">
            <div class="admin-section space-y-4">
                <x-fieldset title="是否啟用註冊" faq="啟用或關閉系統註冊功能">
                    <x-switch name="is_enable_registration" value="1" :checked="(bool) $configs->get('is_enable_registration')" />
                </x-fieldset>
                <x-fieldset title="是否啟用畫廊" faq="啟用或關閉畫廊功能，畫廊只有已登入的使用者可見，畫廊中的圖片均為所有使用者公開的圖片">
                    <x-switch name="is_enable_gallery" value="1" :checked="(bool) $configs->get('is_enable_gallery')" />
                </x-fieldset>
                <x-fieldset title="是否啟用API" faq="啟用或關閉API功能，關閉後將無法透過API上傳圖片、管理圖片等操作">
                    <x-switch name="is_enable_api" value="1" :checked="(bool) $configs->get('is_enable_api')" />
                </x-fieldset>
                <x-fieldset title="是否允許訪客上傳" faq="啟用或關閉訪客上傳功能，訪客上傳受「系統預設組」控制">
                    <x-switch name="is_allow_guest_upload" value="1" :checked="(bool) $configs->get('is_allow_guest_upload')" />
                </x-fieldset>
                <x-fieldset title="帳號驗證" faq="是否強制使用者驗證電子郵件，開啟後使用者必須經過驗證電子郵件後才能上傳圖片，請確保郵件設定正常">
                    <x-switch name="is_user_need_verify" value="1" :checked="(bool) $configs->get('is_user_need_verify')" />
                </x-fieldset>
                <div class="text-right">
                    <x-button type="submit">儲存更改</x-button>
                </div>
            </div>
        </form>

        <p class="admin-section-title">OAuth 2.0 登入</p>
        <form action="{{ route('admin.settings.save') }}">
            <div class="admin-section space-y-4">
                <input type="hidden" name="oauth_enable" value="0">
                <x-fieldset title="是否啟用 OAuth 登入" faq="啟用或關閉第三方 OAuth 2.0 登入功能">
                    <x-switch name="oauth_enable" value="1" :checked="(bool) $configs->get('oauth_enable')" />
                </x-fieldset>
                <input type="hidden" name="oauth_force_login" value="0">
                <x-fieldset title="強制 SSO 登入" faq="開啟後，已綁定 SSO 的使用者只能透過 SSO 登入，密碼登入將被拒絕；尚未綁定 SSO 的使用者不受影響。僅在啟用 OAuth 登入時生效">
                    <x-switch name="oauth_force_login" value="1" :checked="(bool) $configs->get('oauth_force_login')" />
                </x-fieldset>
                <input type="hidden" name="oauth_allow_register" value="0">
                <x-fieldset title="是否允許 OAuth 註冊" faq="開啟後，使用者可透過 OAuth 直接註冊並登入；關閉後，使用者需先使用電子郵件註冊，然後在個人設定中繫結 OAuth 帳號">
                    <x-switch name="oauth_allow_register" value="1" :checked="(bool) $configs->get('oauth_allow_register')" />
                </x-fieldset>
                <input type="hidden" name="oauth_pkce_enable" value="0">
                <x-fieldset title="是否啟用 PKCE" faq="PKCE (Proof Key for Code Exchange) 增強授權碼流程的安全性，推薦開啟">
                    <x-switch name="oauth_pkce_enable" value="1" :checked="(bool) $configs->get('oauth_pkce_enable', true)" />
                </x-fieldset>
                <div>
                    <label for="oauth_provider_name" class="block text-sm font-medium text-slate-700">登入按鈕顯示名稱</label>
                    <x-input type="text" name="oauth_provider_name" id="oauth_provider_name" value="{{ $configs->get('oauth_provider_name') }}" placeholder="例如：GitHub / Google / 企業 SSO"/>
                </div>
                <div>
                    <label for="oauth_client_id" class="block text-sm font-medium text-slate-700"><span class="text-red-600">*</span>Client ID</label>
                    <x-input type="text" name="oauth_client_id" id="oauth_client_id" value="{{ $configs->get('oauth_client_id') }}" placeholder="OAuth 應用的 Client ID" autocomplete="off"/>
                </div>
                <div>
                    <label for="oauth_client_secret" class="block text-sm font-medium text-slate-700"><span class="text-red-600">*</span>Client Secret</label>
                    <x-input type="password" name="oauth_client_secret" id="oauth_client_secret" value="{{ $configs->get('oauth_client_secret') }}" placeholder="OAuth 應用的 Client Secret" autocomplete="new-password"/>
                </div>
                <div>
                    <label for="oauth_authorize_url" class="block text-sm font-medium text-slate-700"><span class="text-red-600">*</span>授權位址</label>
                    <x-input type="url" name="oauth_authorize_url" id="oauth_authorize_url" value="{{ $configs->get('oauth_authorize_url') }}" placeholder="https://provider.example.com/oauth/authorize"/>
                </div>
                <div>
                    <label for="oauth_token_url" class="block text-sm font-medium text-slate-700"><span class="text-red-600">*</span>Token 位址</label>
                    <x-input type="url" name="oauth_token_url" id="oauth_token_url" value="{{ $configs->get('oauth_token_url') }}" placeholder="https://provider.example.com/oauth/token"/>
                </div>
                <div>
                    <label for="oauth_userinfo_url" class="block text-sm font-medium text-slate-700"><span class="text-red-600">*</span>使用者資訊位址</label>
                    <x-input type="url" name="oauth_userinfo_url" id="oauth_userinfo_url" value="{{ $configs->get('oauth_userinfo_url') }}" placeholder="https://provider.example.com/oauth/userinfo"/>
                </div>
                <div class="rounded-xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm text-sky-700 space-y-2 oauth-callback-notice">
                    <p class="font-medium">請在 OAuth 提供方後台設定以下回呼位址：</p>
                    <div>
                        <p class="text-xs text-sky-600 mb-1">登入回呼</p>
                        <x-input type="text" :value="route('oauth.callback')" readonly onclick="this.select()" />
                    </div>
                    <div>
                        <p class="text-xs text-sky-600 mb-1">繫結回呼</p>
                        <x-input type="text" :value="route('oauth.bind.callback')" readonly onclick="this.select()" />
                    </div>
                </div>

                <div>
                    <label for="oauth_scope" class="block text-sm font-medium text-slate-700">Scope</label>
                    <x-input type="text" name="oauth_scope" id="oauth_scope" value="{{ $configs->get('oauth_scope', 'openid profile email') }}" placeholder="openid profile email"/>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label for="oauth_user_id_field" class="block text-sm font-medium text-slate-700">使用者標識欄位</label>
                        <x-input type="text" name="oauth_user_id_field" id="oauth_user_id_field" value="{{ $configs->get('oauth_user_id_field', 'sub') }}" placeholder="sub"/>
                    </div>
                    <div>
                        <label for="oauth_user_name_field" class="block text-sm font-medium text-slate-700">使用者名稱欄位</label>
                        <x-input type="text" name="oauth_user_name_field" id="oauth_user_name_field" value="{{ $configs->get('oauth_user_name_field', 'name') }}" placeholder="name"/>
                    </div>
                    <div>
                        <label for="oauth_user_email_field" class="block text-sm font-medium text-slate-700">電子郵件欄位</label>
                        <x-input type="text" name="oauth_user_email_field" id="oauth_user_email_field" value="{{ $configs->get('oauth_user_email_field', 'email') }}" placeholder="email"/>
                    </div>
                </div>

                <div class="text-right">
                    <x-button type="submit">儲存更改</x-button>
                </div>
            </div>
        </form>

        <p class="admin-section-title">使用者</p>
        <form action="{{ route('admin.settings.save') }}">
            <div class="admin-section space-y-4">
                <div>
                    <label for="user_initial_capacity" class="block text-sm font-medium text-slate-700">使用者初始容量(kb)</label>
                    <x-input type="number" name="user_initial_capacity" id="user_initial_capacity" step="0.01" value="{{ $configs->get('user_initial_capacity') }}" placeholder="請輸入使用者初始容量(kb)"/>
                </div>

                <div class="text-right">
                    <x-button type="submit">儲存更改</x-button>
                </div>
            </div>
        </form>

        <p class="admin-section-title">自訂程式碼</p>
        <form action="{{ route('admin.settings.save') }}">
            <div class="admin-section space-y-4">
                <div>
                    <label for="custom_css" class="block text-sm font-medium text-slate-700">自訂 CSS</label>
                    <p class="text-xs text-slate-400 mb-1.5">自訂樣式將注入到所有頁面的 &lt;head&gt; 中，無需包裹 &lt;style&gt; 標籤</p>
                    <x-textarea name="custom_css" id="custom_css" placeholder="/* 例如：修改主題色 */&#10;:root { --primary: #ef4444; }" rows="8" spellcheck="false">{{ $configs->get('custom_css') }}</x-textarea>
                </div>
                <div>
                    <label for="custom_js" class="block text-sm font-medium text-slate-700">自訂 JavaScript</label>
                    <p class="text-xs text-slate-400 mb-1.5">自訂指令碼將注入到所有頁面的底部，無需包裹 &lt;script&gt; 標籤</p>
                    <x-textarea name="custom_js" id="custom_js" placeholder="// 例如：新增統計程式碼&#10;console.log('Hello Lsky Pro');" rows="8" spellcheck="false">{{ $configs->get('custom_js') }}</x-textarea>
                </div>

                <div class="text-right">
                    <x-button type="submit">儲存更改</x-button>
                </div>
            </div>
        </form>

        <p class="admin-section-title">郵件設定</p>
        <div class="admin-section space-y-4">
            <x-fieldset title="發信驅動">
                <x-fieldset-radio id="mail[default]" name="mail[default]" data-select="mailer" value="smtp" checked>SMTP</x-fieldset-radio>
            </x-fieldset>

            <div class="mb-4 hidden" data-mailer-driver="smtp">
                <form action="{{ route('admin.settings.save') }}" class="space-y-4">
                    <div>
                        <label for="mail[mailers][smtp][host]" class="block text-sm font-medium text-slate-700"><span class="text-red-600">*</span>主機位址</label>
                        <x-input type="text" name="mail[mailers][smtp][host]" id="mail[mailers][smtp][host]" value="{{ $configs['mail']['mailers']['smtp']['host'] ?? '' }}" placeholder="請輸入SMTP 主機位址"/>
                    </div>
                    <div>
                        <label for="mail[mailers][smtp][port]" class="block text-sm font-medium text-slate-700"><span class="text-red-600">*</span>連線連接埠</label>
                        <x-input type="number" name="mail[mailers][smtp][port]" id="mail[mailers][smtp][port]" value="{{ $configs['mail']['mailers']['smtp']['port'] ?? 587 }}" placeholder="請輸入SMTP 主機連線連接埠"/>
                    </div>
                    <div>
                        <label for="mail[mailers][smtp][username]" class="block text-sm font-medium text-slate-700"><span class="text-red-600">*</span>使用者名稱</label>
                        <x-input type="text" name="mail[mailers][smtp][username]" id="mail[mailers][smtp][username]" value="{{ $configs['mail']['mailers']['smtp']['username'] ?? '' }}" placeholder="請輸入使用者名稱" autocomplete="off"/>
                    </div>
                    <div>
                        <label for="mail[mailers][smtp][password]" class="block text-sm font-medium text-slate-700"><span class="text-red-600">*</span>密碼</label>
                        <x-input type="password" name="mail[mailers][smtp][password]" id="mail[mailers][smtp][password]" value="{{ $configs['mail']['mailers']['smtp']['password'] ?? '' }}" placeholder="請輸入密碼" autocomplete="new-password"/>
                    </div>
                    <div>
                        <label for="mail[mailers][smtp][encryption]" class="block text-sm font-medium text-slate-700">加密方式</label>
                        <x-input type="text" name="mail[mailers][smtp][encryption]" id="mail[mailers][smtp][encryption]" value="{{ $configs['mail']['mailers']['smtp']['encryption'] ?? '' }}" placeholder="請輸入加密方式(ssl, tls)"/>
                    </div>
                    <div>
                        <label for="mail[mailers][smtp][timeout]" class="block text-sm font-medium text-slate-700">連線超時時間(秒)</label>
                        <x-input type="number" name="mail[mailers][smtp][timeout]" id="mail[mailers][smtp][timeout]" value="{{ $configs['mail']['mailers']['smtp']['timeout'] ?? 10 }}" placeholder="請輸入連線超時時間(秒)"/>
                    </div>
                    <div>
                        <label for="mail[mailers][smtp][from_address]" class="block text-sm font-medium text-slate-700">發件人位址</label>
                        <x-input type="email" name="mail[from][address]" id="mail[from][address]" value="{{ $configs['mail']['from']['address'] ?? '' }}" placeholder="請輸入發件人電子郵件位址"/>
                    </div>
                    <div>
                        <label for="mail[mailers][smtp][from_name]" class="block text-sm font-medium text-slate-700">發件人名稱</label>
                        <x-input type="text" name="mail[from][name]" id="mail[from][name]" value="{{ $configs['mail']['from']['name'] ?? '' }}" placeholder="請輸入發件人名稱"/>
                    </div>

                    <input type="hidden" name="mail[default]" value="smtp">
                    <input type="hidden" name="mail[mailers][smtp][transport]" value="smtp">

                    <div class="text-right">
                        <x-button type="button" id="mail-test" class="bg-yellow-500">測試</x-button>
                        <x-button type="submit">儲存更改</x-button>
                    </div>
                </form>
            </div>
        </div>

        <p class="admin-section-title">系統升級</p>
        <div class="admin-section">
            <p id="check-update" class="text-gray-600 text-center p-4" style="display: none">
                <i class="fas fa-cog animate-spin"></i> 正在檢查更新...
            </p>
            <p id="not-update" class="text-center p-6" style="display: none">
            <span class="text-slate-700">{{ \App\Utils::config(\App\Enums\ConfigKey::AppVersion) }}</span>
                <span class="text-slate-500">已是最新版本</span>
            </p>
            <div id="have-update" class="break-words" style="display: none"></div>
        </div>
    </div>

    <script type="text/html" id="update-tpl">
        <div class="flex items-center">
            <img id="icon" src="__icon__" alt="icon" class="rounded-full w-16" style="animation-duration: 5s">
            <div class="flex flex-col text-slate-700 ml-4">
                <p class="font-semibold">Lsky Pro __name__</p>
                <p class="text-sm">__size__</p>
                <p class="text-sm">釋出於 __pushed_at__</p>
            </div>
        </div>
        <p id="upgrade-message" class="mt-4 text-sm text-gray-500"></p>
        <div class="mt-4 text-sm markdown-body">
            __changelog__
        </div>
        <div class="mt-6 text-right">
            <a href="javascript:void(0)" id="install" class="rounded-md px-4 py-2 bg-blue-500 text-white">立即安裝</a>
        </div>
    </script>

    @push('scripts')
        <script>
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
                axios.put(this.action, $(this).serialize()).then(function (response) {
                    toastr[response.data.status ? 'success' : 'error'](response.data.message)
                });
            });

            $('#mail-test').click(function () {
                Swal.fire({
                    title: '請輸入接收測試郵件的電子郵件',
                    input: 'text',
                    inputValue: '',
                    inputAttributes: {
                        type: 'email',
                        autocapitalize: 'off'
                    },
                    showCancelButton: true,
                    confirmButtonText: '確認',
                    showLoaderOnConfirm: true,
                    preConfirm: (value) => {
                        return axios.post('{{ route('admin.settings.mail.test') }}', {
                            email: value,
                        }).then(response => {
                            if (! response.data.status) {
                                throw new Error(response.data.message)
                            }
                            return response.data;
                        }).catch(error => Swal.showValidationMessage(error));
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        toastr[result.value.status ? 'success' : 'warning'](result.value.message);
                    }
                })
            });

            let timer;
            let upgrade = function () {
                return {
                    start: function () {
                        $('#icon').addClass('animate-spin')
                        $('#install').attr('disabled', true).removeClass('bg-blue-500').addClass('cursor-not-allowed bg-gray-400').text('執行升級中...')
                        $('#upgrade-message').text('準備升級...').removeClass('text-red-500').addClass('text-gray-500');

                        timer = setInterval(getProgress, 1500);
                        axios.post('{{ route('admin.settings.upgrade') }}');
                    },
                    stop: function () {
                        $('#icon').removeClass('animate-spin')
                        $('#install').attr('disabled', false).removeClass('cursor-not-allowed bg-gray-400').addClass('bg-blue-500').text('立即安裝')
                        clearInterval(timer);
                    }
                };
            };

            let getVersion = function (callback) {
                $('#check-update').show();
                axios.get('{{ route('admin.settings.check.update') }}').then(response => {
                    if (response.data.status && response.data.data.is_update) {
                        $('#check-update').hide();
                        let version = response.data.data.version;
                        let html = $('#update-tpl').html()
                            .replace(/__icon__/g, version.icon)
                            .replace(/__name__/g, version.name)
                            .replace(/__size__/g, version.size)
                            .replace(/__pushed_at__/g, version.pushed_at)
                            .replace(/__changelog__/g, version.changelog);
                        $('#have-update').html(html).show();
                        $('.markdown-body a').attr('target', '_blank');
                        callback && callback(version);
                    } else {
                        $('#not-update').show();
                        $('#check-update').hide();
                    }
                });
            }

            let getProgress = function () {
                axios.get('{{ route('admin.settings.upgrade.progress') }}').then(response => {
                    $('#upgrade-message').text(response.data.data.message);
                    if (response.data.data.status === 'success') {
                        $('#upgrade-message').removeClass('text-gray-500').addClass('text-green-500');
                        $('#install').hide();
                    }
                    if (response.data.data.status === 'fail') {
                        $('#upgrade-message').removeClass('text-gray-500').addClass('text-red-500');
                    }
                    if (response.data.data.status !== 'installing') {
                        upgrade().stop();
                    }
                });
            };

            $(document).on('click', '#install', function () {
                if ($(this).attr('disabled')) {
                    return;
                }
                upgrade().start();
            });

            @if(cache()->has('upgrade_progress'))
                getVersion(() => {
                    $('#icon').addClass('animate-spin')
                    $('#install').attr('disabled', true).removeClass('bg-blue-500').addClass('cursor-not-allowed bg-gray-400').text('正在升級...')
                    $('#upgrade-message').text('請稍等...').removeClass('text-red-500').addClass('text-gray-500');

                    timer = setInterval(getProgress, 1500);
                });
                @else
                getVersion();
            @endif
        </script>
    @endpush

</x-app-layout>
