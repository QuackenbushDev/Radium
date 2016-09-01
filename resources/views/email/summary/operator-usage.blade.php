@extends('email')

@section('content')
    <table>
        <tr>
            <th>
                <table class="spacer">
                    <tbody>
                    <tr>
                        <td height="32px" style="font-size:32px;line-height:32px;">&#xA0;</td>
                    </tr>
                    </tbody>
                </table>
                <table class="spacer">
                    <tbody>
                    <tr>
                        <td height="16px" style="font-size:16px;line-height:16px;">&#xA0;</td>
                    </tr>
                    </tbody>
                </table>
                <h1 class="text-center">{{ $title }}</h1>
                <table class="spacer">
                    <tbody>
                    <tr>
                        <td height="16px" style="font-size:16px;line-height:16px;">&#xA0;</td>
                    </tr>
                    </tbody>
                </table>
                <p>Bandwidth Usage</p>
                <table class="button large expand">
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td>
                                        <img src="{{ $bandwidthGraph }}">
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td class="expander"></td>
                    </tr>
                </table>
                <p>Connections</p>
                <table class="button large expand">
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td>
                                        <img src="{{ $connectionGraph }}">
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td class="expander"></td>
                    </tr>
                </table>
                <hr>
                <p>Nas Usage</p>
                @foreach($nasGraphs as $index => $graph)
                    {{ $nasList[$index]->nasname }}
                    <table class="button large expand">
                        <tr>
                            <td>
                                <table>
                                    <tr>
                                        <td>
                                            <img src="{{ $graph }}">
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td class="expander"></td>
                        </tr>
                    </table>
                @endforeach
            </th>
            <th class="expander"></th>
        </tr>
    </table>
@endsection