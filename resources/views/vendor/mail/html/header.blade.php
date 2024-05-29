@props(['url', 'layout' => false])
<tr>
<td class="header">
    <table style="border-bottom: 3px solid #ffd9b4;box-shadow: 0 0.25rem 1.125rem rgba(75, 70, 92, 0.1);border-spacing:0;text-align:inherit;width:540px;padding:0;background-color:#ffffff;margin: 32px auto 16px; border-radius: .375rem;">
        <tbody>
        <tr>
            <td>
                <div style="text-align:center;padding:32px;" align="center">
                    <a href="{{ $url }}" style="text-decoration:none; display: block; margin-bottom: 0;" target="_blank">
                        @if($layout)
                            @isset($layout['logo'])
                                <img style="max-width:100%;margin:0 auto;" src="{{ $layout['logo'] }}" class="logo" alt="" width="200px">
                            @else
                                {{ $layout['store'] ?? $slot }}
                            @endisset
                       @else
                            <img style="max-width:100%;margin:0 auto;" src="{{ asset('assets/img/logo.png') }}" class="logo" alt="" width="200px">
                            {{--{{ $slot }}--}}
                        @endif
                    </a>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</td>
</tr>
