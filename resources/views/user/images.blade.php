@section('title', '我的圖片')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/justified-gallery/justifiedGallery.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/viewer-js/viewer.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/context-js/context-js.css') }}">
    <link rel="stylesheet" href="{{ asset('css/flatpickr/flatpickr.min.css') }}">
    <style>
        /* flatpickr 主題適配亮/暗色變數 */
        .flatpickr-calendar {
            background: var(--card-bg);
            border: 1px solid var(--border-strong);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.18);
        }
        .flatpickr-calendar.arrowTop:before { border-bottom-color: var(--border-strong); }
        .flatpickr-calendar.arrowTop:after { border-bottom-color: var(--card-bg); }
        .flatpickr-calendar.arrowBottom:before { border-top-color: var(--border-strong); }
        .flatpickr-calendar.arrowBottom:after { border-top-color: var(--card-bg); }
        .flatpickr-months .flatpickr-month,
        .flatpickr-months .flatpickr-prev-month, .flatpickr-months .flatpickr-next-month,
        .flatpickr-weekdays, .flatpickr-weekday, .flatpickr-current-month .flatpickr-monthDropdown-months {
            color: var(--text-primary);
            fill: var(--text-primary);
            background: transparent;
        }
        .flatpickr-day {
            color: var(--text-primary);
            border-color: transparent;
        }
        .flatpickr-day.flatpickr-disabled, .flatpickr-day.prevMonthDay, .flatpickr-day.nextMonthDay {
            color: var(--text-secondary);
            opacity: 0.45;
        }
        .flatpickr-day:hover, .flatpickr-day.prevMonthDay:hover, .flatpickr-day.nextMonthDay:hover {
            background: var(--input-bg);
            border-color: var(--input-border);
        }
        .flatpickr-day.today { border-color: #10b981; }
        .flatpickr-day.today:hover { background: rgba(16, 185, 129, 0.16); color: var(--text-primary); }
        .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange,
        .flatpickr-day.selected:hover, .flatpickr-day.startRange:hover, .flatpickr-day.endRange:hover,
        .flatpickr-day.selected:focus, .flatpickr-day.startRange:focus, .flatpickr-day.endRange:focus {
            background: linear-gradient(135deg, #059669, #0d9488);
            border-color: #059669;
            color: #fff;
        }
        .flatpickr-day.inRange, .flatpickr-day.prevMonthDay.inRange, .flatpickr-day.nextMonthDay.inRange {
            background: rgba(16, 185, 129, 0.14);
            border-color: transparent;
            box-shadow: -5px 0 0 rgba(16, 185, 129, 0.14), 5px 0 0 rgba(16, 185, 129, 0.14);
            color: var(--text-primary);
        }
        .flatpickr-input[readonly] { cursor: pointer; background: var(--input-bg); }
    </style>
@endpush

<x-app-layout>
    <div class="relative flex flex-wrap justify-between items-center gap-y-2 px-3 py-2 z-[3] top-0 left-0 right-0 bg-white/95 backdrop-blur-sm" style="border-bottom: 1px solid var(--toolbar-border); box-shadow: 0 1px 8px rgba(0,0,0,0.04);">
        <div class="flex items-center gap-1">
            <button class="flex items-center gap-1.5 text-xs py-1.5 px-3 rounded-lg text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition-all duration-150 font-medium" onclick="getAlbums()">
                <i class="fas fa-layer-group text-emerald-400 text-xs"></i>相簿
            </button>
            <div class="flex-row hidden lg:flex gap-0.5">
                <a data-operate="movements" class="hidden text-xs py-1.5 px-3 rounded-lg text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition-all duration-150 font-medium" href="javascript:void(0)">移動到相簿</a>
                <a data-operate="remove" class="hidden text-xs py-1.5 px-3 rounded-lg text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition-all duration-150 font-medium" href="javascript:void(0)">移出當前相簿</a>
                <a data-operate="permission" class="hidden text-xs py-1.5 px-3 rounded-lg text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition-all duration-150 font-medium" href="javascript:void(0)">設定權限</a>
                <a data-operate="detail" class="hidden text-xs py-1.5 px-3 rounded-lg text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition-all duration-150 font-medium" href="javascript:void(0)">詳細資訊</a>
                <a data-operate="rename" class="hidden text-xs py-1.5 px-3 rounded-lg text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition-all duration-150 font-medium" href="javascript:void(0)">重新命名</a>
                <a data-operate="delete" class="hidden text-xs py-1.5 px-3 rounded-lg text-red-500 hover:bg-red-50 hover:text-red-600 transition-all duration-150 font-medium" href="javascript:void(0)">刪除</a>
            </div>
            <div class="block lg:hidden">
                <x-dropdown direction="right">
                    <x-slot name="trigger">
                        <button class="text-xs py-1.5 px-3 rounded-lg text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition-all duration-150 flex items-center gap-1">
                            <i class="fas fa-ellipsis-h text-emerald-400"></i>
                            <span>更多</span>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link data-operate="refresh" href="javascript:void(0)" @click="open = false"><i class="fas fa-sync-alt w-4 text-emerald-400"></i>重新整理</x-dropdown-link>
                        <x-dropdown-link data-operate="movements" class="hidden" href="javascript:void(0)" @click="open = false"><i class="fas fa-folder-open w-4 text-emerald-400"></i>移動到相簿</x-dropdown-link>
                        <x-dropdown-link data-operate="remove" class="hidden" href="javascript:void(0)" @click="open = false"><i class="fas fa-folder-minus w-4 text-emerald-400"></i>移出當前相簿</x-dropdown-link>
                        <x-dropdown-link data-operate="permission" class="hidden" href="javascript:void(0)" @click="open = false"><i class="fas fa-eye w-4 text-emerald-400"></i>設定權限</x-dropdown-link>
                        <x-dropdown-link data-operate="detail" class="hidden" href="javascript:void(0)" @click="open = false"><i class="fas fa-info-circle w-4 text-emerald-400"></i>詳細資訊</x-dropdown-link>
                        <x-dropdown-link data-operate="rename" class="hidden" href="javascript:void(0)" @click="open = false"><i class="fas fa-pen w-4 text-emerald-400"></i>重新命名</x-dropdown-link>
                        <x-dropdown-link data-operate="delete" class="hidden text-red-500 hover:bg-red-50 hover:text-red-600" href="javascript:void(0)" @click="open = false"><i class="fas fa-trash w-4"></i>刪除</x-dropdown-link>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <div class="relative hidden md:block">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" id="search" class="pl-8 pr-4 py-1.5 rounded-lg border border-slate-200 bg-slate-50 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 focus:bg-white transition-all duration-200 w-36 hover:w-52 focus:w-52" placeholder="搜尋圖片...">
            </div>
            <x-dropdown direction="left">
                <x-slot name="trigger">
                    <button id="order" class="flex items-center gap-1.5 text-xs py-1.5 px-3 rounded-lg text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition-all duration-150 font-medium border border-slate-200">
                        <span>最新</span>
                        <i class="fas fa-sort text-emerald-400"></i>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link href="javascript:void(0)" @click="setOrderBy('newest'); open = false">最新</x-dropdown-link>
                    <x-dropdown-link href="javascript:void(0)" @click="setOrderBy('earliest'); open = false">最早</x-dropdown-link>
                    <x-dropdown-link href="javascript:void(0)" @click="setOrderBy('utmost'); open = false">最大</x-dropdown-link>
                    <x-dropdown-link href="javascript:void(0)" @click="setOrderBy('least'); open = false">最小</x-dropdown-link>
                </x-slot>
            </x-dropdown>
            <x-dropdown direction="left">
                <x-slot name="trigger">
                    <button id="permission" class="flex items-center gap-1.5 text-xs py-1.5 px-3 rounded-lg text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition-all duration-150 font-medium border border-slate-200">
                        <span>可見</span>
                        <i class="fas fa-filter text-emerald-400"></i>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link href="javascript:void(0)" @click="open = false; setPermission('all')">可見</x-dropdown-link>
                    <x-dropdown-link href="javascript:void(0)" @click="open = false; setPermission('public')">公開</x-dropdown-link>
                    <x-dropdown-link href="javascript:void(0)" @click="open = false; setPermission('private')">私有</x-dropdown-link>
                </x-slot>
            </x-dropdown>
            <div class="relative">
                <button type="button" id="date-range-toggle" class="flex items-center gap-1.5 text-xs py-1.5 px-3 rounded-lg text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition-all duration-150 font-medium border border-slate-200">
                    <span id="date-range-label">全部時間</span>
                    <i class="fas fa-calendar-alt text-emerald-400"></i>
                </button>
                <div id="date-range-panel" class="hidden absolute right-0 mt-2 z-[6] p-3 rounded-xl" style="width: min(19rem, calc(100vw - 1.5rem)); background: var(--card-bg); border: 1px solid var(--border-strong); box-shadow: 0 12px 32px rgba(0,0,0,0.18);">
                    <div class="flex items-end gap-2">
                        <div class="flex-1">
                            <label for="date-start" class="block text-xs font-medium text-slate-500 mb-1">開始日期</label>
                            <input type="text" id="date-start" readonly placeholder="不限" class="w-full text-sm px-2.5 py-1.5 rounded-lg border focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400" style="border-color: var(--input-border); color: var(--text-primary);">
                        </div>
                        <span class="text-slate-400 text-xs pb-2">至</span>
                        <div class="flex-1">
                            <label for="date-end" class="block text-xs font-medium text-slate-500 mb-1">結束日期</label>
                            <input type="text" id="date-end" readonly placeholder="不限" class="w-full text-sm px-2.5 py-1.5 rounded-lg border focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400" style="border-color: var(--input-border); color: var(--text-primary);">
                        </div>
                    </div>
                    <p class="mt-2 text-[11px] text-slate-400">開始日期不能晚於結束日期</p>
                    <div class="flex justify-end gap-2 mt-2">
                        <button type="button" id="date-range-clear" class="text-xs py-1.5 px-3 rounded-lg text-slate-600 hover:bg-slate-100 transition-all duration-150 border border-slate-200">清除</button>
                        <button type="button" id="date-range-apply" class="text-xs py-1.5 px-3 rounded-lg text-white font-medium transition-all duration-150" style="background: linear-gradient(135deg, #059669, #0d9488);">應用</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="relative inset-0 h-full overflow-hidden">
        <!-- content -->
        <div id="images-scroll" class="absolute inset-0 overflow-y-scroll dragselect select-none" style="background: var(--content-surface);">
            <div id="images-grid" class="dragselect"></div>
        </div>
        <!-- right drawer -->
        <div id="drawer-mask" class="absolute hidden inset-0 z-[2]" style="background: rgba(2, 6, 23, 0.56); backdrop-filter: blur(2px);" onclick="drawer.close()"></div>
        <div id="drawer" class="absolute w-64 md:w-72 top-0 -right-[1000px] bottom-0 z-[2] flex flex-col transition-all duration-300" style="background: var(--drawer-bg); border-left: 1px solid var(--drawer-border); box-shadow: -12px 0 32px rgba(0,0,0,0.18);">
            <div class="flex justify-between items-center text-md px-3 py-1 border-b" style="border-color: var(--drawer-border);">
                <span class="truncate text-[var(--text-secondary)]" id="drawer-title"></span>
                <a href="javascript:drawer.close()" class="p-2 text-emerald-500 hover:text-emerald-400 transition-colors"><i class="fas fa-times"></i></a>
            </div>
            <div id="drawer-content" class="overflow-y-auto"></div>
        </div>
    </div>

    <script type="text/html" id="images-item-tpl">
        <a href="javascript:void(0)" data-id="__id__" data-json='__json__' class="images-item relative cursor-default rounded outline outline-2 outline-offset-2 outline-transparent">
            <div class="image-selector absolute z-[2] top-0 right-0 overflow-hidden cursor-pointer sm:hidden group-hover:block">
                <div class="p-1 text-xl sm:text-2xl">
                    <i class="fas fa-check-circle block rounded-full bg-white text-white border border-gray-500 shadow-sm"></i>
                </div>
            </div>
            <div class="image-mask absolute left-0 right-0 bottom-0 h-20 z-[1] bg-gradient-to-t from-black" onclick="$(this).siblings('img').trigger('click')">
                <div class="absolute left-2 bottom-2 text-white z-[2] w-[90%]">
                    <p class="text-sm truncate filename" title="__name__">__name__</p>
                    <p class="text-xs date" title="__human_date__">__date__</p>
                </div>
            </div>
            <img alt="__name__" data-original="__url__" src="__thumb_url__" width="__width__" height="__height__">
        </a>
    </script>

    <script type="text/html" id="albums-container-tpl">
        <div id="albums-container" class="flex flex-col justify-center items-center w-full p-3 space-y-2">
            <div id="album-add" class="flex flex-col w-full hidden border rounded p-2">
                <p class="error-message text-white p-2 mb-2 text-sm bg-red-500 rounded hidden"></p>
                <form class="w-full space-y-2" action="/user/albums">
                    <input type="text" class="w-full rounded px-2.5 py-1.5 text-sm border-0 bg-gray-200" name="name" placeholder="請輸入名稱">
                    <textarea class="w-full resize-y rounded-md text-sm border-0 bg-gray-200" name="intro" placeholder="請輸入簡介"></textarea>
                    <button class="w-full py-1 px-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm text-center tracking-wider font-semibold rounded-md transition-colors">建立相簿</button>
                </form>
            </div>
        </div>
    </script>

    <script type="text/html" id="albums-item-tpl">
        <a href="javascript:void(0)" data-id="__id__" data-json='__json__' title="__intro__" class="albums-item flex justify-between items-center group px-2 h-7 rounded w-full bg-gray-100 text-gray-800 hover:bg-blue-300 hover:text-white border border-transparent transition-colors">
            <span class="text-sm truncate w-[80%] name">__name__</span>
            <div class="flex items-center justify-center space-x-1 hidden group-hover:block">
                <span class="update"><i class="fas fa-edit text-xs"></i></span>
                <span class="delete"><i class="fas fa-trash-alt text-xs text-red-400"></i></span>
            </div>
            <span class="group-hover:hidden text-xs">__image_num__</span>
        </a>
    </script>

    <script type="text/html" id="album-update-tpl">
        <div id="album-edit" data-id="__id__" class="flex flex-col w-full border rounded p-2">
            <p class="error-message text-white p-2 mb-2 text-sm bg-red-500 rounded hidden"></p>
            <form class="w-full space-y-2" action="/user/albums/__id__">
                <input type="text" class="w-full rounded px-2.5 py-1.5 text-sm border-0 bg-gray-200" placeholder="請輸入名稱" name="name" value="__name__">
                <textarea class="w-full resize-y rounded-md text-sm border-0 bg-gray-200" name="intro" placeholder="請輸入簡介">__intro__</textarea>
                <button class="w-full py-1 px-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm text-center tracking-wider font-semibold rounded-md transition-colors">確認修改</button>
            </form>
        </div>
    </script>

    <script type="text/html" id="image-detail-tpl">
        <div class="my-4 px-4 space-y-3 text-[var(--text-primary)]">
            <div>
                <span class="text-sm font-semibold">相簿名稱</span>
                <p class="my-2 break-words text-gray-700">__album_name__</p>
            </div>
            <div>
                <div class="text-sm font-semibold">使用策略</div>
                <p class="my-2 break-words text-gray-600">__strategy_name__</p>
            </div>
            <div>
                <div class="text-sm font-semibold">圖片名稱</div>
                <p class="my-2 break-words text-gray-600">__filename__</p>
            </div>
            <div>
                <div class="text-sm font-semibold">圖片原始名稱</div>
                <p class="my-2 break-words text-gray-600">__origin_name__</p>
            </div>
            <div>
                <div class="text-sm font-semibold">圖片大小</div>
                <p class="my-2 break-words text-gray-600">__size__</p>
            </div>
            <div>
                <div class="text-sm font-semibold">圖片類型</div>
                <p class="my-2 break-words text-gray-600">__mimetype__</p>
            </div>
            <div>
                <div class="text-sm font-semibold">尺寸</div>
                <p class="my-2 break-words text-gray-600">__width__ * __height__</p>
            </div>
            <div>
                <div class="text-sm font-semibold">MD5</div>
                <p class="my-2 break-words text-gray-600">__md5__</p>
            </div>
            <div>
                <div class="text-sm font-semibold">SHA-128</div>
                <p class="my-2 break-words text-gray-600">__sha1__</p>
            </div>
            <div>
                <div class="text-sm font-semibold">權限</div>
                <p class="my-2 break-words text-gray-600">__permission__</p>
            </div>
            <div>
                <div class="text-sm font-semibold">上傳 IP</div>
                <p class="my-2 break-words text-gray-600">__uploaded_ip__</p>
            </div>
            <div>
                <div class="text-sm font-semibold">上傳時間</div>
                <p class="my-2 break-words text-gray-600">__created_at__</p>
            </div>
        </div>
    </script>

    @push('scripts')
        <script src="{{ asset('js/justified-gallery/jquery.justifiedGallery.min.js') }}"></script>
        <script src="{{ asset('js/viewer-js/viewer.min.js') }}"></script>
        <script src="{{ asset('js/dragselect/ds.min.js') }}"></script>
        <script src="{{ asset('js/context-js/context-js.js') }}"></script>
        <script src="{{ asset('js/clipboard/index.browser.js') }}"></script>
        <script src="{{ asset('js/clipboard/clipboard.min.js') }}"></script>
        <script src="{{ asset('js/flatpickr/flatpickr.min.js') }}"></script>
        <script src="{{ asset('js/flatpickr/zh_tw.js') }}"></script>
        <script>
            let gridConfigs = {
                rowHeight: 180,
                margins: 16,
                captions: false,
                border: 10,
                waitThumbnailsLoad: false,
            };

            let selectedAlbum = {}; // 選擇的相簿

            const HEADER_TITLE = '#header-title';
            const IMAGES_SCROLL = '#images-scroll';
            const IMAGES_GRID = '#images-grid';
            const IMAGES_ITEM = '.images-item';
            const ALBUM_ITEM = '.albums-item';

            const $headerTitle = $(HEADER_TITLE);
            const $photos = $(IMAGES_GRID);
            const $drawer = $("#drawer");
            const $drawerMask = $('#drawer-mask');
            const viewer = new Viewer(document.getElementById('images-grid'), {url: 'data-original'});
            const drawer = {
                open(title, content, callback) {
                    $drawerMask.fadeIn();
                    $drawer.css('right', 0);
                    $drawer.find('#drawer-title').html(title);
                    $drawer.find('#drawer-content').html(content);
                    callback && callback();
                },
                close(callback) {
                    $drawerMask.fadeOut();
                    $drawer.css('right', '-1000px');
                    albumsInfinite && albumsInfinite.destroy();
                    callback && callback();
                },
                toggle(title, content, callback) {
                    if ($drawerMask.is(':hidden')) {
                        this.open(title, content, callback);
                    } else {
                        this.close(callback);
                    }
                }
            }

            $photos.justifiedGallery(gridConfigs);

            let albumsInfinite = null;
            const imagesInfinite = utils.infiniteScroll(IMAGES_SCROLL, {
                url: '{{ route('user.images') }}',
                classes: ['dragselect'],
                success: function (response) {
                    if (!response.status) {
                        return toastr.error(response.message);
                    }

                    let images = response.data.images.data;
                    if (images.length <= 0 || response.data.images.current_page === response.data.images.last_page) {
                        this.finished = true;
                    }

                    let html = '';
                    for (const i in images) {
                        html += $('#images-item-tpl').html()
                            .replace(/__id__/g, images[i].id)
                            .replace(/__name__/g, images[i].filename.replace(/\$/g, '$$$$'))
                            .replace(/__human_date__/g, images[i].human_date)
                            .replace(/__date__/g, images[i].date)
                            .replace(/__url__/g, images[i].url)
                            .replace(/__thumb_url__/g, images[i].thumb_url)
                            .replace(/__width__/g, images[i].width)
                            .replace(/__height__/g, images[i].height)
                            .replace(/__json__/g, JSON.stringify(images[i]).replace(/\$/g, '$$$$'))
                    }

                    $photos.append(html);
                    ds.setSelectables($photos.find(IMAGES_ITEM));
                },
                complete: function () {
                    if ($photos.html() !== '') {
                        // 由於 justifiedGallery 建立後佔高度(無論是否有內容或內容被清空)，導致載入過程中在沒有資料的情況下高度被拉開
                        // 所以需要在重置前銷燬，重置資料後重新構建 justifiedGallery
                        if ($photos.hasClass('reset')) {
                            $photos.justifiedGallery(gridConfigs).removeClass('reset');
                        }

                        $photos.justifiedGallery('norewind')
                        viewer.update();
                    } else {
                        // 沒有任何資料時銷燬 justifiedGallery
                        $photos.justifiedGallery('destroy')
                    }
                    $headerTitle.text('我的圖片')
                }
            });

            const resetImages = (params) => {
                $photos.addClass('reset').html('').justifiedGallery('destroy');
                ds.clearSelection();
                params = $.extend({page: 1}, params)
                imagesInfinite.refresh(params);
            }

            const getAlbums = (options, callback) => {
                let title = '__title__ <i class="cursor-pointer fas fa-plus text-emerald-500" onclick="$(\'#album-add\').toggleClass(\'hidden\')"></i>'.replace(/__title__/g, (options || {}).title || '我的相簿');
                let content = $('#albums-container-tpl').html();
                drawer.toggle(title, content, function () {
                    let $albums = $('#albums-container');
                    const CREATE_ID = '#album-add';
                    const UPDATE_ID = '#album-edit';
                    albumsInfinite = utils.infiniteScroll('#drawer-content', {
                        url: '{{ route('user.albums') }}',
                        success: function (response) {
                            if (!response.status) {
                                return toastr.error(response.message);
                            }

                            let albums = response.data.albums.data;
                            if (albums.length <= 0 || response.data.albums.current_page === response.data.albums.last_page) {
                                this.finished = true;
                            }

                            let html = '';
                            for (const i in albums) {
                                let item = $('#albums-item-tpl').html()
                                    .replace(/__id__/g, albums[i].id)
                                    .replace(/__name__/g, albums[i].name)
                                    .replace(/__intro__/g, albums[i].intro)
                                    .replace(/__image_num__/g, albums[i].image_num)
                                    .replace(/__json__/g, JSON.stringify(albums[i]))
                                if (albums[i].id === selectedAlbum.id) {
                                    // 選取的相簿高亮
                                    item = item
                                        .replace(/bg-gray-100/g, 'bg-blue-400')
                                        .replace(/text-gray-800/g, 'text-white')
                                }

                                html += item;
                            }

                            $albums.append(html);

                            callback && callback.call(this, $albums.get(0));
                        }
                    });

                    $albums.off('click', '>a').on('click', '>a', function () {
                        // 如果當前已經為選取狀態則清除
                        if (selectedAlbum.id === $(this).data('id')) {
                            selectedAlbum = {};
                        } else {
                            selectedAlbum = $(this).data('json');
                        }
                        resetImages({page: 1, album_id: selectedAlbum.id || null});
                        drawer.close();
                        ds.clearSelection();
                    });

                    const resetAlbums = () => {
                        $albums.find('>a').remove();
                        $albums.find(CREATE_ID).addClass('hidden');
                        $albums.find(UPDATE_ID).remove();
                        albumsInfinite.refresh({page: 1});
                    }

                    $albums.off('click', '.update').on('click', '.update', function (e) {
                        e.stopPropagation();
                        let selectedId = $albums.find(UPDATE_ID).data('id');
                        let $item = $(this).closest('a.albums-item');
                        $albums.find(UPDATE_ID).remove();
                        if (selectedId !== $item.data('id')) {
                            $item.after($('#album-update-tpl').html()
                                .replace(/__id__/g, $item.data('id'))
                                .replace(/__name__/g, $item.find('>span').html())
                                .replace(/__intro__/g, $item.attr('title'))
                            );
                        }
                    });

                    $albums.off('click', '.delete').on('click', '.delete', function (e) {
                        e.stopPropagation();
                        Swal.fire({
                            title: '確認刪除該相簿?',
                            text: "刪除後相簿中的圖片將會被移出。",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: '確認',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                let id = $(this).closest(ALBUM_ITEM).data('id');
                                axios.delete(`/user/albums/${id}`).then(response => {
                                    if (response.data.status) {
                                        selectedAlbum = {};
                                        resetImages();
                                        setTimeout(_ => drawer.close(), 300)
                                    } else {
                                        toastr.error(response.data.message);
                                    }
                                });
                            }
                        })
                    });

                    // confirm create
                    $albums.off('submit', CREATE_ID + ' form').on('submit', CREATE_ID + ' form', function (e) {
                        e.preventDefault();
                        let $form = $(this);
                        axios.post($form.attr('action'), $form.serialize()).then(response => {
                            let $errorMessage = $albums.find(CREATE_ID + ' .error-message').html('').hide();
                            if (response.data.status) {
                                $form.get(0).reset();
                                resetAlbums()
                            } else {
                                $errorMessage.html('<i class="fas fa-exclamation-circle"></i> ' + response.data.message).show();
                            }
                        });
                    });

                    // confirm update
                    $albums.off('submit', UPDATE_ID + ' form').on('submit', UPDATE_ID + ' form', function (e) {
                        e.preventDefault();
                        let $form = $(this);
                        axios.put($form.attr('action'), $form.serialize()).then(response => {
                            let $errorMessage = $albums.find(UPDATE_ID + ' .error-message').html('').hide();
                            if (response.data.status) {
                                let $editContainer = $(this).closest(UPDATE_ID);
                                $albums.find(`>a[data-id=${$editContainer.data('id')}]`)
                                    .attr('title', $form.find('textarea').val())
                                    .find('.name').text($form.find('input').val());
                                $editContainer.remove();
                            } else {
                                $errorMessage.html('<i class="fas fa-exclamation-circle"></i> ' + response.data.message).show();
                            }
                        });
                    });
                });
            }

            const setOrderBy = function (sort) {
                resetImages({page: 1, order: sort})
                $('#order span').text({newest: '最新', earliest: '最早', utmost: '最大', least: '最小'}[sort]);
            };

            const setPermission = function (permission) {
                resetImages({page: 1, permission: permission})
                $('#permission span').text({public: '公開', private: '私有', all: '可見'}[permission]);
            };

            // 時間範圍篩選（flatpickr）
            const dateStartPicker = flatpickr('#date-start', {
                locale: 'zh_tw',
                dateFormat: 'Y-m-d',
                disableMobile: true,
                onChange: function (selectedDates, dateStr) {
                    // 開始日期限制結束日期可選的最小值
                    dateEndPicker.set('minDate', dateStr || null);
                },
            });

            const dateEndPicker = flatpickr('#date-end', {
                locale: 'zh_tw',
                dateFormat: 'Y-m-d',
                disableMobile: true,
                onChange: function (selectedDates, dateStr) {
                    // 結束日期限制開始日期可選的最大值
                    dateStartPicker.set('maxDate', dateStr || null);
                },
            });

            const updateDateRangeLabel = function (start, end) {
                if (start && end) {
                    $('#date-range-label').text(`${start} ~ ${end}`);
                } else if (start) {
                    $('#date-range-label').text(`${start} 之後`);
                } else if (end) {
                    $('#date-range-label').text(`${end} 之前`);
                } else {
                    $('#date-range-label').text('全部時間');
                }
            };

            $('#date-range-toggle').click(function (e) {
                e.stopPropagation();
                $('#date-range-panel').toggleClass('hidden');
            });

            $(document).click(function (e) {
                // flatpickr 的日曆掛載在 body 上，需單獨排除，避免選擇日期時收起面板
                if (!$(e.target).closest('#date-range-panel, #date-range-toggle, .flatpickr-calendar').length) {
                    $('#date-range-panel').addClass('hidden');
                }
            });

            $('#date-range-apply').click(function () {
                const start = $('#date-start').val();
                const end = $('#date-end').val();

                // 前者不得晚於後者
                if (start && end && start > end) {
                    return toastr.error('開始日期不能晚於結束日期');
                }

                resetImages({page: 1, start_date: start || '', end_date: end || ''});
                updateDateRangeLabel(start, end);
                $('#date-range-panel').addClass('hidden');
            });

            $('#date-range-clear').click(function () {
                dateStartPicker.clear();
                dateEndPicker.clear();
                dateStartPicker.set('maxDate', null);
                dateEndPicker.set('minDate', null);
                resetImages({page: 1, start_date: '', end_date: ''});
                updateDateRangeLabel('', '');
            });

            $('#search').keydown(function (e) {
                if (e.keyCode === 13) {
                    resetImages({page: 1, keyword: $(this).val()});
                }
            });

            $(document).keydown(e => {
                if (e.keyCode === 65 && (e.altKey || e.metaKey)) {
                    e.preventDefault();
                    ds.setSelection($(IMAGES_ITEM));
                }
            });
        </script>
        <script>
            const ds = new DragSelect({
                area: $(IMAGES_SCROLL).get(0),
                keyboardDrag: false,
            });

            const bindOperates = () => {
                let selected = ds.getSelection();
                if (selected.length) {
                    $headerTitle.text(`已選擇 ${selected.length} 張圖片`);
                } else {
                    $headerTitle.text('我的圖片');
                }
                $('[data-operate]').hide();
                let operates = [];
                if (selected.length === 0) {
                    operates = ['refresh'];
                }
                if (selected.length === 1) {
                    operates = ['refresh', 'movements', 'permission', 'detail', 'rename', 'delete'];
                }
                if (selected.length > 1) {
                    operates = ['refresh', 'movements', 'permission', 'delete'];
                }
                if (selected.length && selectedAlbum.id !== undefined) {
                    operates.push('remove');
                }
                $(operates.map(item => `[data-operate=${item}]`).toString()).css('display', 'block');
            };

            ds.subscribe('predragstart', ({ event }) => {
                if (utils.isMobile()) {
                    ds.stop();
                }

                if (! $(event.target).hasClass('dragselect')) {
                    ds.break();
                }
            });
            ds.subscribe('elementselect', _ => bindOperates());
            ds.subscribe('elementunselect', _ => bindOperates());

            $photos.on('click', '.image-selector', function () {
                ds.toggleSelection($(this).closest('a'));
                bindOperates();
            })
        </script>
        <script>
            context.init({
                fadeSpeed: 100,
                filter: function ($obj) {},
                above: 'auto',
                preventDoubleContext: true,
                compress: false
            });

            new ClipboardJS('.dropdown-menu li a.copy', {
                text: function(trigger) {
                    return $(trigger).data('copy-value');
                }
            }).on('success', _ => {
                toastr.success('複製成功');
            }).on('error', _ => {
                toastr.warning('複製失敗')
            });

            const methods = {
                movements() {
                    getAlbums({title: '選擇相簿'}, e => {
                        let selected = ds.getSelection().map(item => $(item).data('id'));
                        $headerTitle.text(`移動 ${selected.length} 張圖片至...`)
                        $(e).off('click', '>a').on('click', '>a', function () {
                            axios.put('{{ route('user.images.movement') }}', {
                                selected: selected,
                                id: $(this).data('id'),
                                album_id: selectedAlbum.id || 0,
                            }).then(response => {
                                if (response.data.status) {
                                    drawer.close();
                                    resetImages();
                                    toastr.success(response.data.message);
                                } else {
                                    toastr.warning(response.data.message);
                                }
                            })
                        });
                    });
                },
                remove() {
                    let selected = ds.getSelection().map(item => $(item).data('id'));
                    $headerTitle.text(`移出 ${selected.length} 張圖片`)
                    axios.put('{{ route('user.images.movement') }}', {
                        selected: selected,
                        album_id: selectedAlbum.id || null, // 原相簿ID
                        id: null,
                    }).then(response => {
                        if (response.data.status) {
                            drawer.close();
                            resetImages();
                            toastr.success(response.data.message);
                        } else {
                            toastr.warning(response.data.message);
                        }
                    });
                },
                permission() {
                    Swal.fire({
                        title: '選擇一個權限',
                        text: '選擇公開將會出現在畫廊中(若平臺開啟了畫廊)',
                        input: 'select',
                        inputOptions: {
                            public: '公開',
                            private: '私有',
                        },
                        confirmButtonText: '確認設定',
                        inputPlaceholder: '請選擇一個權限',
                        showCancelButton: true,
                        inputValidator: (value) => {
                            return new Promise((resolve) => {
                                if (value === '') {
                                    resolve('請選擇正確的權限')
                                } else {
                                    resolve();
                                }
                            })
                        }
                    }).then(result => {
                        if (result.isConfirmed) {
                            let selected = ds.getSelection().map(item => $(item).data('id'));
                            axios.put('{{ route('user.images.permission') }}', {
                                ids: selected,
                                permission: result.value,
                            }).then(response => {
                                if (response.data.status) {
                                    ds.clearSelection();
                                    toastr.success(response.data.message);
                                } else {
                                    toastr.warning(response.data.message);
                                }
                            });
                        }
                    });
                },
                rename(e) {
                    let item = $(e).data('json');
                    Swal.fire({
                        title: '請輸入圖片名稱',
                        input: 'text',
                        inputValue: item.filename,
                        inputAttributes: {
                            autocapitalize: 'off'
                        },
                        showCancelButton: true,
                        confirmButtonText: '確認',
                        showLoaderOnConfirm: true,
                        preConfirm: (value) => {
                            return axios.put('{{ route('user.images.rename') }}', {
                                id: item.id,
                                name: value,
                            }).then(response => {
                                if (! response.data.status) {
                                    throw new Error(response.data.message)
                                }
                                return response.data;
                            }).catch(error => Swal.showValidationMessage('服務異常，請稍後再試。'));
                        },
                        allowOutsideClick: () => !Swal.isLoading()
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (result.value.status) {
                                $(e).find('p.filename').attr('title', result.value.data.filename).text(result.value.data.filename)
                                item.filename = result.value.data.filename;
                                $(e).data('json', item);
                                toastr.success(result.value.message);
                            } else {
                                toastr.error(result.value.message);
                            }
                        }
                    })
                },
                delete() {
                    Swal.fire({
                        title: '確認要刪除選取的圖片？',
                        text: "刪除後不可恢復，記錄和檔案同時刪除",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: '確認刪除',
                        cancelButtonText: '取消',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let selected = ds.getSelection().map(item => $(item).data('id'));
                            axios.delete('{{ route('user.images.delete') }}', {
                                data: selected,
                            }).then(response => {
                                if (response.data.status) {
                                    let size = 0;
                                    ds.getSelection().map(item => {
                                        size += $(item).data('json').size;
                                        $(item).remove();
                                    });
                                    utils.setCapacityProgress(-size);
                                    $headerTitle.text('我的圖片');
                                    $photos.justifiedGallery(gridConfigs).removeClass('reset');
                                    toastr.success(response.data.message);
                                } else {
                                    toastr.warning(response.data.message);
                                }
                            });
                        }
                    });
                },
                detail(e) {
                    let item = $(e).data('json');
                    axios.get(`/user/images/${item.id}`).then(response => {
                        if (response.data.status) {
                            let image = response.data.data.image;
                            let content = $('#image-detail-tpl').html()
                                .replace(/__album_name__/g, image.album ? image.album.name : '-')
                                .replace(/__strategy_name__/g, image.strategy ? image.strategy.name : '-')
                                .replace(/__filename__/g, image.filename.replace(/\$/g, '$$$$'))
                                .replace(/__origin_name__/g, image.origin_name.replace(/\$/g, '$$$$'))
                                .replace(/__size__/g, utils.formatSize(image.size * 1024))
                                .replace(/__mimetype__/g, image.mimetype)
                                .replace(/__width__/g, image.width)
                                .replace(/__height__/g, image.height)
                                .replace(/__md5__/g, image.md5)
                                .replace(/__sha1__/g, image.sha1)
                                .replace(/__permission__/g, image.permission === 1 ? '公開' : '私有')
                                .replace(/__uploaded_ip__/g, image.uploaded_ip)
                                .replace(/__created_at__/g, image.created_at)
                            drawer.open(item.filename, content);
                        } else {
                            toastr.error(response.data.message);
                        }
                    })
                }
            };
            // right click actions
            const actions = {
                copy: {
                    text: '複製圖片',
                    action: e => {
                        let src = $(e).find('img').attr('src');
                        CopyImageClipboard.copyImageToClipboard(src).then(() => {
                            toastr.success('複製成功')
                        }).catch(e => {
                            toastr.warning('複製失敗, ' + e.message)
                        });
                    },
                    visible: () => ds.getSelection().length === 1,
                },
                refresh: {text: '重新整理', action: _ => resetImages()},
                rename: {
                    text: '重新命名',
                    visible: () => ds.getSelection().length === 1,
                    action: e => methods.rename(e),
                },
                open: {
                    text: '新視窗開啟',
                    action: e => window.open($(e).data('json').url),
                    visible: () => ds.getSelection().length === 1,
                },
                copies: {
                    text: '複製連結',
                    subMenu: [
                        {
                            text: 'Url',
                            classes: ['copy'],
                            attributes: {"data-link-type": "url"},
                        },
                        {
                            text: 'Html',
                            classes: ['copy'],
                            attributes: {"data-link-type": "html"},
                        },
                        {
                            text: 'BBCode',
                            classes: ['copy'],
                            attributes: {"data-link-type": "bbcode"},
                        },
                        {
                            text: 'Markdown',
                            classes: ['copy'],
                            attributes: {"data-link-type": "markdown"},
                        },
                        {
                            text: 'Markdown with link',
                            classes: ['copy'],
                            attributes: {"data-link-type": "markdown_with_link"},
                        },
                        {
                            text: 'Thumbnail url',
                            classes: ['copy'],
                            attributes: {"data-link-type": "thumbnail_url"},
                        },
                    ],
                    visible: () => ds.getSelection().length === 1,
                },
                movements: {
                    text: '移動到相簿',
                    action: _ => methods.movements(),
                },
                remove: {
                    text: '移出當前相簿',
                    action: _ => methods.remove(),
                    visible: _ => selectedAlbum.id !== undefined,
                },
                detail: {
                    text: '詳細資訊',
                    visible: () => ds.getSelection().length === 1,
                    action: e => methods.detail(e)
                },
                delete: {
                    text: '刪除',
                    action: _ => methods.delete(),
                },
                permission: {
                    text: '設定權限',
                    action: _ => methods.permission(),
                },
            };
            // right click 'images scroll' container
            context.attach(IMAGES_SCROLL, {
                data: [actions.refresh],
                beforeOpen: () => ds.clearSelection(),
            });
            // right click image
            context.attach(IMAGES_ITEM, {
                data: [
                    {header: '圖片操作'},
                    actions.refresh,
                    actions.copy,
                    actions.copies,
                    actions.open,
                    actions.movements,
                    actions.remove,
                    actions.permission,
                    actions.detail,
                    {divider: true},
                    actions.rename,
                    actions.delete,
                ],
                beforeOpen: function (item) {
                    // 選取當前專案
                    if (ds.getSelection().length <= 1) ds.clearSelection();
                    ds.addSelection($(item));
                },
                afterOpen: function (item, dropdown) {
                    let data = $(item).data('json');
                    // 追加連結
                    $(dropdown).find('a.copy').each(function () {
                        $(this).data('copy-value', data.links[$(this).data('link-type')])
                    });
                }
            });
            // the operates functions
            $('[data-operate]').click(function () {
                let operate = $(this).data('operate');
                let selected = ds.getSelection();

                if (selected.length === 0) {
                    return false;
                }

                switch (operate) {
                    case 'refresh': // 重新整理
                        resetImages();
                        break;
                    case 'movements': // 移動到相簿
                        methods.movements();
                        break;
                    case 'remove': // 移出當前相簿
                        methods.remove();
                        break;
                    case 'rename': // 重新命名
                        methods.rename(selected[0]);
                        break;
                    case 'permission': // 設定權限
                        methods.permission();
                        break;
                    case 'detail':
                        methods.detail(selected[0]);
                        break;
                    case 'delete': // 刪除
                        methods.delete();
                        break;
                }
            });
        </script>
    @endpush
</x-app-layout>
