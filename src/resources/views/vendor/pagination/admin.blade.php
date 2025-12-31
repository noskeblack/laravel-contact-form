@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-center w-full">
        {{-- デスクトップ表示（768px以上） --}}
        <div class="hidden md:flex md:items-center md:justify-center md:w-full md:flex-col md:gap-2">
            {{-- 結果表示 --}}
            <div class="text-center">
                <p class="text-sm text-gray-700">
                    {!! __('Showing') !!}
                    @if ($paginator->firstItem())
                        <span class="font-medium">{{ $paginator->firstItem() }}</span>
                        {!! __('to') !!}
                        <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    {!! __('of') !!}
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>

            {{-- ページネーションコントロール（デスクトップ） --}}
            <div class="flex justify-center items-center space-x-1 flex-wrap">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                        <span class="flex items-center justify-center w-10 h-10 text-sm font-medium text-gray-400 bg-white border border-gray-300 cursor-not-allowed rounded-md">
                            &lt;
                        </span>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="flex items-center justify-center w-10 h-10 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-1 active:bg-gray-100 transition ease-in-out duration-150" aria-label="{{ __('pagination.previous') }}">
                        &lt;
                    </a>
                @endif

                {{-- Pagination Elements（ページ番号のみ） --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                        {{-- "..." セパレーターのみ表示、<< と >> は表示しない --}}
                        @if ($element !== '«' && $element !== '»' && $element !== '&laquo;' && $element !== '&raquo;' && $element !== '<<' && $element !== '>>')
                            <span aria-disabled="true">
                                <span class="flex items-center justify-center w-10 h-10 text-sm font-medium text-gray-700 bg-white border border-gray-300 cursor-default rounded-md">
                                    {{ $element }}
                                </span>
                            </span>
                        @endif
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            {{-- << と >> のページ番号は表示しない --}}
                            @if ($page !== '«' && $page !== '»' && $page !== '&laquo;' && $page !== '&raquo;' && $page !== '<<' && $page !== '>>' && is_numeric($page))
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="flex items-center justify-center w-10 h-10 text-sm font-medium text-white bg-gray-700 border border-gray-700 cursor-default rounded-md">
                                            {{ $page }}
                                        </span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="flex items-center justify-center w-10 h-10 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-1 active:bg-gray-100 transition ease-in-out duration-150" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="flex items-center justify-center w-10 h-10 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-1 active:bg-gray-100 transition ease-in-out duration-150" aria-label="{{ __('pagination.next') }}">
                        &gt;
                    </a>
                @else
                    <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                        <span class="flex items-center justify-center w-10 h-10 text-sm font-medium text-gray-400 bg-white border border-gray-300 cursor-not-allowed rounded-md">
                            &gt;
                        </span>
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif
