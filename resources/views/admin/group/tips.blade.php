@if(! ini_get('file_uploads'))
    <p class="bg-red-500 p-2 mb-2 rounded text-sm text-white">
        <i class="fas fa-exclamation-circle"></i> 當前系統監測到執行環境關閉了 HTTP 上傳檔案權限(file_uploads=off)，請更改 PHP 此項設定，否則無法上傳檔案。
    </p>
@endif
<p class="bg-yellow-500 p-2 mb-2 rounded text-sm text-white">
    <i class="fas fa-exclamation-circle"></i> 系統執行環境允許上傳大小的最大值為 {{ ini_get('upload_max_filesize') }}，最大 POST 資料大小為 {{ ini_get('post_max_size') }}，上傳檔案大小不得超過這兩項設定值。
</p>
<p class="bg-yellow-500 p-2 mb-2 rounded text-sm text-white">
    <i class="fas fa-exclamation-circle"></i> 原圖保護以及浮水印功能，原理是使用 PHP 接管圖片請求，動態處理後快取之後透過載入快取到記憶體中輸出圖片，對伺服器有著較高的要求，請謹慎使用。如果你使用第三方儲存，蘭空圖床更推薦你使用第三方儲存的圖片處理規則。
</p>
