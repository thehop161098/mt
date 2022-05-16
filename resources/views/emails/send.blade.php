<table width="100%" cellpadding="0" cellspacing="0" role="presentation"
       style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;margin:0;padding:0;width:100%">
    <tbody>
    <tr>
        <td align="center" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box">
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                   style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;margin:0;padding:0;width:100%">
                <tbody>
                <tr>
                    <td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;padding:25px 0;text-align:center;background-color:#4a6df5">
                        @php
                            $pathLogo = isset(config('settings')['logoFE']) ? 'images/settings/'.config('settings')['logoFE'] : '';
                        @endphp
                        <img src="{{ asset($pathLogo) }}" alt="ByBit" width="100">
                    </td>
                </tr>
                {!!$content!!}
                <tr>
                    <td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;background-color: #4a6df5;">
                        <table align="center" width="570" cellpadding="0" cellspacing="0" role="presentation"
                               style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;margin:0 auto;padding:0;text-align:center;width:570px">
                            <tbody>
                            <tr>
                                <td align="center"
                                    style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;padding:35px">
                                    <p style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;line-height:1.5em;margin-top:0;color:white;font-size:12px;text-align:center">
                                       Copyright © 2022 ByBit Limited. All rights reserved
                                    </p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
