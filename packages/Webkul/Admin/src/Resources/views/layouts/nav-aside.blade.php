<div class="aside-nav">

    {{-- button for collapsing aside nav --}}
    <nav-slide-button icon-class="accordian-left-icon"></nav-slide-button>

    <ul>

        <?php $keys = explode('.', $menu->currentKey);

        ?>

        @if(isset($keys) && strlen($keys[0]))
            @foreach (\Illuminate\Support\Arr::get($menu->items, current($keys) . '.children') as $item)
                <li class="{{ $menu->getActive($item) }}">
                    <a href="{{ $item['url'] }}">
                        {{ trans($item['name']) }}

                        @if ($menu->getActive($item))
                            <i class="angle-right-icon"></i>
                        @endif
                    </a>
                </li>
            @endforeach
        @endif
    </ul>
</div>
