<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="relative min-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lsky Pro 安裝程式</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://jsd.liiiu.cn/gh/willow-god/Sharding-fonts/GuanKiapTsingKhai/result.min.css">
    <link rel="stylesheet" href="{{ asset('css/fontawesome.css') }}">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</head>
<body class="font-sans antialiased">
<div class="min-h-screen text-gray-900 bg-gray-100">
    <div class="min-h-screen flex justify-center items-center px-6 py-10 md:py-20">
        <div class="md:w-[600px]">
            <h1 class="text-gray-700 text-3xl">Install Lsky Pro</h1>
            <div id="checking" class="mt-4 p-6 rounded-md bg-white w-full">
                <p class="text-md mb-4 block text-center">Step 1 > 執行環境檢測</p>

                <div class="relative rounded-md bg-white overflow-hidden space-y-4 max-h-[500px] overflow-y-auto">
                    @foreach($extensions as $extension)
                        <dl>
                            <div class="rounded-md bg-gray-50 px-3 py-3 flex items-center justify-between">
                                <dt class="w-[95%] text-md font-medium text-gray-700 {{ ! $extension['result'] ? 'text-red-500' : '' }}">
                                    {{ $extension['name'] }}
                                    <p class="mt-2 text-sm text-gary-400">{{ $extension['intro'] }}</p>
                                </dt>
                                <dd class="mt-1 text-md text-gray-900 truncate">
                                    @if($extension['result'])
                                        <i class="fas fa-check text-green-500"></i>
                                    @else
                                        <i class="fas text-lg fa-times text-red-500"></i>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    @endforeach
                </div>

                <div class="mt-6 text-sm text-gray-500">
                    <p><i class="fas fa-exclamation-circle"></i> 請確保<b>資料庫版本</b>達到要求。</p>
                    <p><i class="fas fa-exclamation-circle"></i> 請確保程式<b>目錄、檔案的權限</b>設定正確。</p>
                    <p><i class="fas fa-exclamation-circle"></i> 出現安裝擴充、啟用函式後未生效，請嘗試<b>重啟 PHP</b>。</p>
                </div>

                <div class="mt-6 text-right">
                    @if($status)
                        <a href="javascript:void(0)" id="next" class="rounded-md px-4 py-2 bg-blue-500 text-white">下一步</a>
                    @else
                        <span class="rounded-md px-4 py-2 bg-gray-400 text-white" disabled>檢測未透過</span>
                    @endif
                </div>
            </div>

            <div id="installing" class="mt-4 p-6 rounded-md bg-white w-full hidden">
                <p class="text-md mb-4 block text-center">Step 2 > 設定資料庫和管理員帳號</p>

                <form class="w-full" method="post">
                    <div class="px-3 py-4 bg-white sm:p-6">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6">
                                <label for="connection" class="block text-sm font-medium text-gray-700">資料庫類型</label>
                                <x-select id="connection" name="connection">
                                    <option value="mysql">MySQL 5.7+</option>
                                    <option value="pgsql">PostgreSQL 9.6+</option>
                                    <option value="sqlite">SQLite 3.8.8+</option>
                                    <option value="sqlsrv">SQL Server 2017+</option>
                                </x-select>
                                <p class="mt-2 text-sm text-red-500 hidden"></p>
                            </div>
                            <div class="col-span-6">
                                <label for="host" class="block text-sm font-medium text-gray-700">資料庫連線位址</label>
                                <x-input type="text" name="host" id="host" placeholder="請輸入資料庫連線位址" value="127.0.0.1"/>
                            </div>
                            <div class="col-span-6">
                                <label for="port" class="block text-sm font-medium text-gray-700">資料庫連線連接埠</label>
                                <x-input type="number" name="port" id="port" placeholder="請輸入資料庫連線連接埠" value="3306"/>
                            </div>
                            <div class="col-span-6">
                                <label for="database" class="block text-sm font-medium text-gray-700">資料庫名稱/路徑</label>
                                <x-input type="text" name="database" id="database" placeholder="請輸入資料庫名稱/路徑" value=""/>
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <label for="username" class="block text-sm font-medium text-gray-700">資料庫使用者名稱</label>
                                <x-input type="text" name="username" id="username" placeholder="請輸入資料庫使用者名稱" value="root"/>
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <label for="password" class="block text-sm font-medium text-gray-700">資料庫密碼</label>
                                <x-input type="password" name="password" id="password" placeholder="請輸入資料庫密碼" value="root"/>
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <label for="email" class="block text-sm font-medium text-gray-700"><span class="text-red-500">*</span>管理員帳號電子郵件</label>
                                <x-input type="email" name="account[email]" id="email" placeholder="請輸入管理員帳號電子郵件"/>
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <label for="admin-password" class="block text-sm font-medium text-gray-700"><span class="text-red-500">*</span>管理員帳號密碼</label>
                                <x-input type="password" name="account[password]" id="admin-password" placeholder="請輸入管理員帳號密碼"/>
                            </div>
                        </div>
                    </div>
                </form>

                <p id="response" class="rounded-md text-red-500 bg-slate-700 p-4 hidden overflow-auto"></p>

                <div class="mt-6 text-right">
                    <a href="javascript:void(0)" id="install" class="rounded-md px-4 py-2 bg-blue-500 text-white">立即安裝</a>
                </div>
            </div>

            <div id="success" class="mt-4 p-6 rounded-md bg-white w-full hidden">
                <i class="fas fa-check-circle text-5xl text-teal-500"></i>
                <p class="mt-4 text-lg">程式安裝完成。</p>
                <div class="mt-3 space-y-1 text-gray-600">
                    <p>你可以點選 <a class="text-green-500" href="{{ route('/') }}">這裡</a> 去首頁。</p>
                    <p>更多幫助請造訪 <a class="text-green-500" href="https://www.lsky.pro" target="_blank">https://www.lsky.pro</a>。</p>
                    <p>使用過程中出現任何問題請務必閱讀 <a class="text-green-500" href="https://docs.lsky.pro" target="_blank">官方文件</a>。</p>
                    <p>如果你覺得蘭空圖床給你帶來幫助，不妨請我喝一杯咖啡☕️  <a class="text-green-500" href="https://github.com/wisp-x/lsky-pro#-%E6%8D%90%E8%B5%A0" target="_blank">去打賞</a>。</p>
                    <p>這個頁面將在下次存取時返回 404 錯誤，如果你想要重新安裝，請刪除程式根目錄中的 installed.lock 檔案，然後重新造訪首頁。</p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script>
    $('#next').click(function () {
        $('#checking').remove();
        $('#installing').removeClass('hidden');
    });

    $('#connection').change(function () {
        let selector = '#host, #port, #username, #password';
        $(selector).parent().show();
        let message = '';
        switch ($(this).val()) {
            case 'pgsql':
                break;
            case 'sqlite':
                message = 'SQLite 資料庫本質上只是一個存在你檔案系統上的檔案，下面的資料庫路徑如果為空，程式將會自動在根目錄 database 資料夾中建立 database.sqlite 檔案，否則請填寫該資料庫檔案的絕對路徑。';
                $(selector).parent().hide();
                break;
            case 'sqlsrv':
                message = '在使用 SQL Server 資料庫前，你需要先確保你已安裝並啟用了 sqlsrv 和 pdo_sqlsrv PHP 擴充套件以及它們所需要的依賴項，例如 Microsoft SQL ODBC 驅動。';
                break;
            default:
        }
        if (message) {
            $(this).siblings('p').text(message).removeClass('hidden');
        } else {
            $(this).siblings('p').text(message).addClass('hidden');
        }
    });

    $('#install').click(function () {
        if ($(this).attr('disabled')) {
            return;
        }
        $(this).attr('disabled', true).removeClass('bg-blue-500').addClass('cursor-not-allowed bg-gray-400').text('執行安裝中...')
        $('#response').html('').addClass('hidden');
        axios.post('{{ route('install') }}', $('#installing form').serialize()).then(response => {
            $('#response').addClass('hidden')
            if (response.data.status) {
                $('#installing').hide();
                $('#success').show();
            } else {
                $('#response').html(response.data.message + ' ' +(response.data.data.response || '')).removeClass('hidden');
            }
        }).finally(() => {
            $(this).attr('disabled', false).removeClass('cursor-not-allowed bg-gray-400').addClass('bg-blue-500').text('立即安裝')
        });
    });
</script>
</html>
