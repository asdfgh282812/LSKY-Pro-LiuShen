@section('title', 'API')

<x-app-layout>
    <div class="my-6 md:my-9">
        <p class="text-xl mb-2 text-gray-800 font-semibold">API說明</p>
        <div class="space-y-4 bg-white p-3 rounded-md mb-10 shadow-custom">
            <div>
                <p class="text-lg text-gray-700 font-semibold">APIURL</p>
                <x-code>{{ request()->getSchemeAndHttpHost() }}/api/v1</x-code>
            </div>

            <div>
                <p class="text-lg text-gray-700 font-semibold">驗證方式</p>
                <div class="my-2 text-sm bg-white rounded-md p-4 overflow-x-auto">
                    當前版本API採用 「HTTP 基本驗證」的方式驗證授權，取得到 token 後，透過設定請求 header 標頭來驗證請求(Bearer Token)，例如：
                    <b class="block my-2 text-gray-600 text-sm">"Authorization": "Bearer 1|1bJbwlqBfnggmOMEZqXT5XusaIwqiZjCDs7r1Ob5"</b>
                    <p class="text-sm">如果未設定 Authorization 的情況下請求上傳API，將會被視為訪客上傳。</p>
                </div>
            </div>

            <div class="my-4 overflow-x-auto">
                <p class="text-sm mb-2">公共請求 headers 說明</p>
                <table class="min-w-full">
                    <thead class="bg-white border">
                    <tr>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            欄位
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            類型
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            說明
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white border divide-y text-sm">
                    <tr>
                        <td class="px-3 py-2 whitespace-nowrap">Authorization</td>
                        <td class="px-3 py-2 whitespace-nowrap">String</td>
                        <td class="px-3 py-2 whitespace-nowrap">授權 Token，例如：Bearer 1|1bJbwlqBfnggmOMEZqXT5XusaIwqiZjCDs7r1Ob5</td>
                    </tr>
                    <tr>
                        <td class="px-3 py-2 whitespace-nowrap"><span class="text-red-500">*</span>Accept</td>
                        <td class="px-3 py-2 whitespace-nowrap">String</td>
                        <td class="px-3 py-2 whitespace-nowrap">必須設定為 application/json</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="my-4 overflow-x-auto">
                <p class="text-sm mb-2">公共響應 headers 說明</p>
                <table class="min-w-full">
                    <thead class="bg-white border">
                    <tr>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            欄位
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            類型
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            說明
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white border divide-y text-sm">
                    <tr>
                        <td class="px-3 py-2 whitespace-nowrap">X-RateLimit-Limit</td>
                        <td class="px-3 py-2 whitespace-nowrap">Integer</td>
                        <td class="px-3 py-2 whitespace-nowrap">當前客戶端一分鐘內請求配額</td>
                    </tr>
                    <tr>
                        <td class="px-3 py-2 whitespace-nowrap">X-RateLimit-Remaining</td>
                        <td class="px-3 py-2 whitespace-nowrap">Integer</td>
                        <td class="px-3 py-2 whitespace-nowrap">當前客戶端剩餘請求配額</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="my-4 overflow-x-auto">
                <p class="text-sm mb-2">響應狀態碼 HTTP Status Code 說明</p>
                <table class="min-w-full">
                    <thead class="bg-white border">
                    <tr>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            狀態碼
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            說明
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white border divide-y text-sm">
                    <tr>
                        <td class="px-3 py-2 whitespace-nowrap">401</td>
                        <td class="px-3 py-2 whitespace-nowrap">未登入或授權失敗</td>
                    </tr>
                    <tr>
                        <td class="px-3 py-2 whitespace-nowrap">403</td>
                        <td class="px-3 py-2 whitespace-nowrap">管理員關閉了API功能</td>
                    </tr>
                    <tr>
                        <td class="px-3 py-2 whitespace-nowrap">429</td>
                        <td class="px-3 py-2 whitespace-nowrap">超出請求配額，請求受限</td>
                    </tr>
                    <tr>
                        <td class="px-3 py-2 whitespace-nowrap">500</td>
                        <td class="px-3 py-2 whitespace-nowrap">服務端出現異常</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <p class="text-sm my-2 text-red-500">文件中API的請求參數，使用紅色「*」符號標註，則表示為必傳項。</p>
        </div>

        <p class="text-xl mb-2 text-gray-800 font-semibold">授權相關</p>
        <div class="space-y-4 bg-white p-3 rounded-md mb-10 shadow-custom">
            <div>
                <p class="text-lg text-gray-700 font-semibold">生成 Token</p>
                <x-code><span class="text-green-500 select-none">POST </span>/tokens</x-code>
                <p class="text-sm text-slate-500 mt-2">支援透過 <span class="font-medium">abilities</span> 指定金鑰權限；未傳時預設授予全部權限。</p>
                <div class="my-4 overflow-x-auto">
                    <p class="text-sm mb-2">請求參數(Body)</p>
                    <table class="min-w-full">
                        <thead class="bg-white border">
                        <tr>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                欄位
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                類型
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                說明
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white border divide-y text-sm">
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap"><span class="text-red-500">*</span>email</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">電子郵件</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap"><span class="text-red-500">*</span>password</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">密碼</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">abilities</td>
                            <td class="px-3 py-2 whitespace-nowrap">String[]</td>
                            <td class="px-3 py-2 whitespace-nowrap">可選權限陣列，未傳時預設為全部權限</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-6">images.read</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">取得圖片列表</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-6">images.upload</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">上傳圖片</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-6">images.delete</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">刪除圖片</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-6">albums.read</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">取得相簿列表</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-6">albums.delete</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">刪除相簿</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-6">profile.read</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">取得使用者資料</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-6">strategies.read</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">取得可用策略</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="my-4 overflow-x-auto">
                    <p class="text-sm mb-2">回傳參數</p>
                    <table class="min-w-full">
                        <thead class="bg-white border">
                        <tr>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                欄位
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                類型
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                說明
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white border divide-y text-sm">
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">status</td>
                            <td class="px-3 py-2 whitespace-nowrap">Boolean</td>
                            <td class="px-3 py-2 whitespace-nowrap">狀態，true 或 false</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">message</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">描述資訊</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">data</td>
                            <td class="px-3 py-2 whitespace-nowrap">Object</td>
                            <td class="px-3 py-2 whitespace-nowrap">資料</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-6">token</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">Token</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-6">abilities</td>
                            <td class="px-3 py-2 whitespace-nowrap">String[]</td>
                            <td class="px-3 py-2 whitespace-nowrap">已授予的權限列表</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div>
                <p class="text-lg text-gray-700 font-semibold">清空 Token</p>
                <x-code><span class="text-red-300 select-none">DELETE </span>/tokens</x-code>
                <div class="my-4 overflow-x-auto">
                    <p class="text-sm mb-2">回傳參數</p>
                    <table class="min-w-full">
                        <thead class="bg-white border">
                        <tr>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                欄位
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                類型
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                說明
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white border divide-y text-sm">
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">status</td>
                            <td class="px-3 py-2 whitespace-nowrap">Boolean</td>
                            <td class="px-3 py-2 whitespace-nowrap">狀態，true 或 false</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">message</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">描述資訊</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">data</td>
                            <td class="px-3 py-2 whitespace-nowrap">Object</td>
                            <td class="px-3 py-2 whitespace-nowrap">資料</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div>
                <p class="text-lg text-gray-700 font-semibold">使用者資料</p>
                <x-code><span class="text-sky-500 select-none">GET </span>/profile</x-code>
                <p class="text-sm text-slate-500 mt-2">所需權限：<span class="font-medium">profile.read</span></p>
                <div class="my-4 overflow-x-auto">
                    <p class="text-sm mb-2">回傳參數</p>
                    <table class="min-w-full">
                        <thead class="bg-white border">
                        <tr>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                欄位
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                類型
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                說明
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white border divide-y text-sm">
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">status</td>
                            <td class="px-3 py-2 whitespace-nowrap">Boolean</td>
                            <td class="px-3 py-2 whitespace-nowrap">狀態，true 或 false</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">message</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">描述資訊</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">data</td>
                            <td class="px-3 py-2 whitespace-nowrap">Object</td>
                            <td class="px-3 py-2 whitespace-nowrap">資料</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-6">name</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">使用者名稱</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-6">avatar</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">頭像位址</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-6">email</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">電子郵件位址</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-6">capacity</td>
                            <td class="px-3 py-2 whitespace-nowrap">Float</td>
                            <td class="px-3 py-2 whitespace-nowrap">總容量</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-6">used_capacity</td>
                            <td class="px-3 py-2 whitespace-nowrap">Float</td>
                            <td class="px-3 py-2 whitespace-nowrap">已使用容量</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-6">url</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">個人主頁位址</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-6">image_num</td>
                            <td class="px-3 py-2 whitespace-nowrap">Integer</td>
                            <td class="px-3 py-2 whitespace-nowrap">圖片數量</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-6">album_num</td>
                            <td class="px-3 py-2 whitespace-nowrap">Integer</td>
                            <td class="px-3 py-2 whitespace-nowrap">相簿數量</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-6">registered_ip</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">註冊 IP</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <p class="text-xl mb-2 text-gray-800 font-semibold">策略相關</p>
        <div class="space-y-4 bg-white p-3 rounded-md mb-10 shadow-custom">
            <div>
                <p class="text-lg text-gray-700 font-semibold">策略列表</p>
                <x-code><span class="text-sky-500 select-none">GET </span>/strategies</x-code>
                <p class="text-sm text-slate-500 mt-2">訪客存取保持不變；攜帶 Token 請求時所需權限：<span class="font-medium">strategies.read</span></p>

                <div class="my-4 overflow-x-auto">
                    <p class="text-sm mb-2">請求參數(Query)</p>
                    <table class="min-w-full">
                        <thead class="bg-white border">
                        <tr>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                欄位
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                類型
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                說明
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white border divide-y text-sm">
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">keyword</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">篩選關鍵字</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="my-4 overflow-x-auto">
                    <p class="text-sm mb-2">回傳參數</p>
                    <table class="min-w-full">
                        <thead class="bg-white border">
                        <tr>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                欄位
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                類型
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                說明
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white border divide-y text-sm">
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">status</td>
                            <td class="px-3 py-2 whitespace-nowrap">Boolean</td>
                            <td class="px-3 py-2 whitespace-nowrap">狀態，true 或 false</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">message</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">描述資訊</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">data</td>
                            <td class="px-3 py-2 whitespace-nowrap">Object</td>
                            <td class="px-3 py-2 whitespace-nowrap">資料</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-6">strategies</td>
                            <td class="px-3 py-2 whitespace-nowrap">Object[]</td>
                            <td class="px-3 py-2 whitespace-nowrap">策略資料</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-10">id</td>
                            <td class="px-3 py-2 whitespace-nowrap">Integer</td>
                            <td class="px-3 py-2 whitespace-nowrap">策略 ID</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-10">name</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">策略名稱</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <p class="text-xl mb-2 text-gray-800 font-semibold">圖片相關</p>
        <div class="space-y-4 bg-white p-3 rounded-md mb-10 shadow-custom">
            <div>
                <p class="text-lg text-gray-700 font-semibold">上傳圖片</p>
                <x-code><span class="text-green-500 select-none">POST </span>/upload</x-code>
                <p class="text-sm text-slate-500 mt-2">訪客上傳保持不變；攜帶 Token 請求時所需權限：<span class="font-medium">images.upload</span></p>

                <div class="my-4 overflow-x-auto">
                    <p class="text-sm mb-2">Headers</p>
                    <table class="min-w-full">
                        <thead class="bg-white border">
                        <tr>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                欄位
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                類型
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                說明
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white border divide-y text-sm">
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap"><span class="text-red-500">*</span>Content-Type</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">需要設定為 multipart/form-data</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="my-4 overflow-x-auto">
                    <p class="text-sm mb-2">請求參數(Body)</p>
                    <table class="min-w-full">
                        <thead class="bg-white border">
                        <tr>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                欄位
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                類型
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                說明
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white border divide-y text-sm">
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap"><span class="text-red-500">*</span>file</td>
                            <td class="px-3 py-2 whitespace-nowrap">File</td>
                            <td class="px-3 py-2 whitespace-nowrap">圖片檔案</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">strategy_id</td>
                            <td class="px-3 py-2 whitespace-nowrap">Integer</td>
                            <td class="px-3 py-2 whitespace-nowrap">儲存策略ID</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="my-4 overflow-x-auto">
                    <p class="text-sm mb-2">回傳參數</p>
                    <table class="min-w-full">
                        <thead class="bg-white border">
                        <tr>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                欄位
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                類型
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                說明
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white border divide-y text-sm">
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">status</td>
                            <td class="px-3 py-2 whitespace-nowrap">Boolean</td>
                            <td class="px-3 py-2 whitespace-nowrap">狀態，true 或 false</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">message</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">描述資訊</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">data</td>
                            <td class="px-3 py-2 whitespace-nowrap">Object</td>
                            <td class="px-3 py-2 whitespace-nowrap">資料</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-6">key</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">圖片唯一金鑰</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-6">name</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">圖片名稱</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-6">pathname</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">圖片路徑名</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-6">origin_name</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">圖片原始名</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-6">size</td>
                            <td class="px-3 py-2 whitespace-nowrap">Float</td>
                            <td class="px-3 py-2 whitespace-nowrap">圖片大小，單位 KB</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-6">mimetype</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">圖片類型</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-6">extension</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">圖片擴充名</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-6">md5</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">圖片 md5 值</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-6">sha1</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">圖片 sha1 值</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-6">links</td>
                            <td class="px-3 py-2 whitespace-nowrap">Object</td>
                            <td class="px-3 py-2 whitespace-nowrap">連結</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-10">url</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">圖片存取 url</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-10">html</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">-</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-10">bbcode</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">-</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-10">markdown</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">-</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-10">markdown_with_link</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">-</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-10">thumbnail_url</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">縮圖 url</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div>
                <p class="text-lg text-gray-700 font-semibold">圖片列表</p>
                <x-code><span class="text-sky-500 select-none">GET </span>/images</x-code>
                <p class="text-sm text-slate-500 mt-2">所需權限：<span class="font-medium">images.read</span></p>

                <div class="my-4 overflow-x-auto">
                    <p class="text-sm mb-2">請求參數(Query)</p>
                    <table class="min-w-full">
                        <thead class="bg-white border">
                        <tr>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                欄位
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                類型
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                說明
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white border divide-y text-sm">
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">page</td>
                            <td class="px-3 py-2 whitespace-nowrap">Integer</td>
                            <td class="px-3 py-2 whitespace-nowrap">頁碼</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">order</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">排序方式，newest=最新，earliest=最早，utmost=最大，least=最小</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">permission</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">權限，public=公開的，private=私有的</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">album_id</td>
                            <td class="px-3 py-2 whitespace-nowrap">Integer</td>
                            <td class="px-3 py-2 whitespace-nowrap">相簿 ID</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">keyword</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">篩選關鍵字</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">start_date</td>
                            <td class="px-3 py-2 whitespace-nowrap">Date</td>
                            <td class="px-3 py-2 whitespace-nowrap">開始日期，格式 Y-m-d，篩選該日期（含）之後上傳的圖片</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">end_date</td>
                            <td class="px-3 py-2 whitespace-nowrap">Date</td>
                            <td class="px-3 py-2 whitespace-nowrap">結束日期，格式 Y-m-d，篩選該日期（含）之前上傳的圖片</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="my-4 overflow-x-auto">
                    <p class="text-sm mb-2">回傳參數</p>
                    <table class="min-w-full">
                        <thead class="bg-white border">
                        <tr>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                欄位
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                類型
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                說明
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white border divide-y text-sm">
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">status</td>
                            <td class="px-3 py-2 whitespace-nowrap">Boolean</td>
                            <td class="px-3 py-2 whitespace-nowrap">狀態，true 或 false</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">message</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">描述資訊</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">data</td>
                            <td class="px-3 py-2 whitespace-nowrap">Object</td>
                            <td class="px-3 py-2 whitespace-nowrap">資料</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-6">current_page</td>
                            <td class="px-3 py-2 whitespace-nowrap">Integer</td>
                            <td class="px-3 py-2 whitespace-nowrap">當前所在頁頁碼</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-6">last_page</td>
                            <td class="px-3 py-2 whitespace-nowrap">Integer</td>
                            <td class="px-3 py-2 whitespace-nowrap">最後一頁頁碼</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-6">per_page</td>
                            <td class="px-3 py-2 whitespace-nowrap">Integer</td>
                            <td class="px-3 py-2 whitespace-nowrap">每頁展示資料數量</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-6">total</td>
                            <td class="px-3 py-2 whitespace-nowrap">Integer</td>
                            <td class="px-3 py-2 whitespace-nowrap">圖片總數量</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-6">data</td>
                            <td class="px-3 py-2 whitespace-nowrap">Object[]</td>
                            <td class="px-3 py-2 whitespace-nowrap">圖片列表</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-10">key</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">圖片唯一金鑰</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-10">name</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">圖片名稱</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-10">origin_name</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">圖片原始名稱</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-10">pathname</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">圖片路徑名</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-10">size</td>
                            <td class="px-3 py-2 whitespace-nowrap">Float</td>
                            <td class="px-3 py-2 whitespace-nowrap">圖片大小，單位 KB</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-10">width</td>
                            <td class="px-3 py-2 whitespace-nowrap">Integer</td>
                            <td class="px-3 py-2 whitespace-nowrap">圖片寬度</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-10">height</td>
                            <td class="px-3 py-2 whitespace-nowrap">Integer</td>
                            <td class="px-3 py-2 whitespace-nowrap">圖片高度</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-10">md5</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">圖片 md5 值</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-10">sha1</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">圖片 sha1 值</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-10">human_date</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">上傳時間(友好格式)</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-10">date</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">上傳日期(yyyy-MM-dd HH:mm:ss)</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-10">links</td>
                            <td class="px-3 py-2 whitespace-nowrap">Object</td>
                            <td class="px-3 py-2 whitespace-nowrap">連結，與上傳API回傳參數中的 links 相同</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div>
                <p class="text-lg text-gray-700 font-semibold">刪除圖片</p>
                <x-code><span class="text-red-300 select-none">DELETE </span>/images/:key</x-code>
                <p class="text-sm text-slate-500 mt-2">所需權限：<span class="font-medium">images.delete</span></p>

                <div class="my-4 overflow-x-auto">
                    <p class="text-sm mb-2">請求參數(Params)</p>
                    <table class="min-w-full">
                        <thead class="bg-white border">
                        <tr>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                欄位
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                類型
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                說明
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white border divide-y text-sm">
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap"><span class="text-red-500">*</span>key</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">圖片金鑰</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="my-4 overflow-x-auto">
                    <p class="text-sm mb-2">回傳參數</p>
                    <table class="min-w-full">
                        <thead class="bg-white border">
                        <tr>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                欄位
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                類型
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                說明
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white border divide-y text-sm">
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">status</td>
                            <td class="px-3 py-2 whitespace-nowrap">Boolean</td>
                            <td class="px-3 py-2 whitespace-nowrap">狀態，true 或 false</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">message</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">描述資訊</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">data</td>
                            <td class="px-3 py-2 whitespace-nowrap">Object</td>
                            <td class="px-3 py-2 whitespace-nowrap">資料</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <p class="text-xl mb-2 text-gray-800 font-semibold">相簿相關</p>
        <div class="space-y-4 bg-white p-3 rounded-md mb-10 shadow-custom">
            <div>
                <p class="text-lg text-gray-700 font-semibold">相簿列表</p>
                <x-code><span class="text-sky-500 select-none">GET </span>/albums</x-code>
                <p class="text-sm text-slate-500 mt-2">所需權限：<span class="font-medium">albums.read</span></p>

                <div class="my-4 overflow-x-auto">
                    <p class="text-sm mb-2">請求參數(Query)</p>
                    <table class="min-w-full">
                        <thead class="bg-white border">
                        <tr>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                欄位
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                類型
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                說明
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white border divide-y text-sm">
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">page</td>
                            <td class="px-3 py-2 whitespace-nowrap">Integer</td>
                            <td class="px-3 py-2 whitespace-nowrap">頁碼</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">order</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">排序方式，newest=最新，earliest=最早，most=圖片最多，least=圖片最少</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">keyword</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">篩選關鍵字</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="my-4 overflow-x-auto">
                    <p class="text-sm mb-2">回傳參數</p>
                    <table class="min-w-full">
                        <thead class="bg-white border">
                        <tr>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                欄位
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                類型
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                說明
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white border divide-y text-sm">
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">status</td>
                            <td class="px-3 py-2 whitespace-nowrap">Boolean</td>
                            <td class="px-3 py-2 whitespace-nowrap">狀態，true 或 false</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">message</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">描述資訊</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">data</td>
                            <td class="px-3 py-2 whitespace-nowrap">Object</td>
                            <td class="px-3 py-2 whitespace-nowrap">資料</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-6">current_page</td>
                            <td class="px-3 py-2 whitespace-nowrap">Integer</td>
                            <td class="px-3 py-2 whitespace-nowrap">當前所在頁頁碼</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-6">last_page</td>
                            <td class="px-3 py-2 whitespace-nowrap">Integer</td>
                            <td class="px-3 py-2 whitespace-nowrap">最後一頁頁碼</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-6">per_page</td>
                            <td class="px-3 py-2 whitespace-nowrap">Integer</td>
                            <td class="px-3 py-2 whitespace-nowrap">每頁展示資料數量</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-6">total</td>
                            <td class="px-3 py-2 whitespace-nowrap">Integer</td>
                            <td class="px-3 py-2 whitespace-nowrap">圖片總數量</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-6">data</td>
                            <td class="px-3 py-2 whitespace-nowrap">Object[]</td>
                            <td class="px-3 py-2 whitespace-nowrap">相簿列表</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-10">id</td>
                            <td class="px-3 py-2 whitespace-nowrap">Integer</td>
                            <td class="px-3 py-2 whitespace-nowrap">相簿自增 ID</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-10">name</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">相簿名稱</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-10">intro</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">相簿簡介</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap pl-10">image_num</td>
                            <td class="px-3 py-2 whitespace-nowrap">Integer</td>
                            <td class="px-3 py-2 whitespace-nowrap">相簿圖片數量</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div>
                <p class="text-lg text-gray-700 font-semibold">刪除相簿</p>
                <x-code><span class="text-red-300 select-none">DELETE </span>/albums/:id</x-code>
                <p class="text-sm text-slate-500 mt-2">所需權限：<span class="font-medium">albums.delete</span></p>

                <div class="my-4 overflow-x-auto">
                    <p class="text-sm mb-2">請求參數(Params)</p>
                    <table class="min-w-full">
                        <thead class="bg-white border">
                        <tr>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                欄位
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                類型
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                說明
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white border divide-y text-sm">
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap"><span class="text-red-500">*</span>id</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">相簿自增 ID</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="my-4 overflow-x-auto">
                    <p class="text-sm mb-2">回傳參數</p>
                    <table class="min-w-full">
                        <thead class="bg-white border">
                        <tr>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                欄位
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                類型
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                說明
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white border divide-y text-sm">
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">status</td>
                            <td class="px-3 py-2 whitespace-nowrap">Boolean</td>
                            <td class="px-3 py-2 whitespace-nowrap">狀態，true 或 false</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">message</td>
                            <td class="px-3 py-2 whitespace-nowrap">String</td>
                            <td class="px-3 py-2 whitespace-nowrap">描述資訊</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">data</td>
                            <td class="px-3 py-2 whitespace-nowrap">Object</td>
                            <td class="px-3 py-2 whitespace-nowrap">資料</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
