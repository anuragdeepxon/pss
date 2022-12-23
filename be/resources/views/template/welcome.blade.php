<div style="background-color: #e1f3fa; margin-top: 20px; margin-right: 10px; margin-bottom: 20px;">
    <table cellpadding="25" style="margin: 0px auto;">
        <tbody>
            <tr>
                <td>
                    <table cellpadding="24" width="584px" style="margin: 0 auto; max-width: 584px; background-color: #e1f3fa; border: 1px solid #a8adad;">
                        <tbody>
                            <tr>
                                <td><h2 style="color: #0088c7;"><b>{{config('app.name')}}</b></td>
                            </tr>
                        </tbody>
                    </table>
                    <table cellpadding="24" style="background: #fff; border: 1px solid #a8adad; width: 584px; border-top: none; color: #4d4b48; font-family: Arial, Helvetica, sans-serif; font-size: 13px; line-height: 18px; box-shadow: 0px 2px 2px #A8ADAD;">
                        <tbody>
                            <tr>
                                <td>
                                    <p style="margin-top: 0; margin-bottom: 20px;">Hello {{$users->full_name}},</p>
                                    <p style="margin-top: 0; margin-bottom: 10px;">Thank You for registering with us:&nbsp;</p>
                                    <p style="margin-top: 0; margin-bottom: 0px; font-size: 11px;">Disclaimer: This email and any files transmitted with it are confidential and intended solely for the use of the individual or entity to whom they are addressed.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</div>