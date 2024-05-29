<li @class(['menu-item', 'active' => Route::is($menu['active'])])>
    <a href="{{ $menu['route'] }}" class="menu-link">
        <i @class(['menu-icon tf-icons ti', $menu['icon']])></i>
        {{ trans($menu['text']) }}
    </a>
</li>
