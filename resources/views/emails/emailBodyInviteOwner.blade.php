<style>
        /* What it does: Remove spaces around the email design added by some email clients. */
        /* Beware: It can remove the padding / margin and add a background color to the compose a reply window. */
        html,
        body {
            margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
            background: #f1f1f1;
        }

        /* What it does: Stops email clients resizing small text. */
        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        /* What it does: Centers email on Android 4.4 */
        div[style*="margin: 16px 0"] {
            margin: 0 !important;
        }

        /* What it does: Stops Outlook from adding extra spacing to tables. */
        table,
        td {
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }

        /* What it does: Fixes webkit padding issue. */
        table {
            border-spacing: 0 !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            margin: 0 auto !important;
        }

        /* What it does: Uses a better rendering method when resizing images in IE. */
        img {
            -ms-interpolation-mode: bicubic;
        }

        /* What it does: Prevents Windows 10 Mail from underlining links despite inline CSS. Styles for underlined links should be inline. */
        a {
            text-decoration: none;
        }

        /* What it does: A work-around for email clients meddling in triggered links. */
        *[x-apple-data-detectors],
        /* iOS */
        .unstyle-auto-detected-links *,
        .aBn {
            border-bottom: 0 !important;
            cursor: default !important;
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        /* What it does: Prevents Gmail from displaying a download button on large, non-linked images. */
        .a6S {
            display: none !important;
            opacity: 0.01 !important;
        }

        /* What it does: Prevents Gmail from changing the text color in conversation threads. */
        .im {
            color: inherit !important;
        }

        /* If the above doesn't work, add a .g-img class to any image in question. */
        img.g-img+div {
            display: none !important;
        }

        /* What it does: Removes right gutter in Gmail iOS app: https://github.com/TedGoas/Cerberus/issues/89  */
        /* Create one of these media queries for each additional viewport size you'd like to fix */

        /* iPhone 4, 4S, 5, 5S, 5C, and 5SE */
        @media only screen and (min-device-width: 320px) and (max-device-width: 374px) {
            u~div .email-container {
                min-width: 320px !important;
            }
        }

        /* iPhone 6, 6S, 7, 8, and X */
        @media only screen and (min-device-width: 375px) and (max-device-width: 413px) {
            u~div .email-container {
                min-width: 375px !important;
            }
        }

        /* iPhone 6+, 7+, and 8+ */
        @media only screen and (min-device-width: 414px) {
            u~div .email-container {
                min-width: 414px !important;
            }
        }
    </style>

    <!-- CSS Reset : END -->

    <!-- Progressive Enhancements : BEGIN -->
    <style>
        .primary {
            background: #17bebb;
        }

        .bg_white {
            background: #F0F0F0;
        }

        .bg_light {
            background: #f7fafa;
        }

        .bg_black {
            background: #000000;
        }

        .bg_dark {
            background: rgba(0, 0, 0, .8);
        }

        .email-section {
            padding: 2.5em;
        }

        /*BUTTON*/
        .btn {
            padding: 10px 15px;
            display: inline-block;
        }

        .btn.btn-primary {
            border-radius: 5px;
            background: #17bebb;
            color: #ffffff;
        }

        .btn.btn-white {
            border-radius: 5px;
            background: #ffffff;
            color: #000000;
        }

        .btn.btn-white-outline {
            border-radius: 5px;
            background: transparent;
            border: 1px solid #fff;
            color: #fff;
        }

        .btn.btn-black-outline {
            border-radius: 0px;
            background: transparent;
            border: 2px solid #000;
            color: #000;
            font-weight: 700;
        }

        .btn-custom {
            color: rgba(0, 0, 0, .3);
            text-decoration: underline;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Roboto';
            color: #000000;
            margin-top: 0;
            font-weight: 400;
        }

        body {
            font-family: 'Roboto';
            font-weight: 400;
            font-size: 15px;
            line-height: 1.8;
            color: rgba(0, 0, 0, .4);
        }

        a {
            color: #17bebb;
        }

        /*LOGO*/
        .logo {
            padding-top: 15px;
            background: url('./images/logo-header.png') no-repeat center/cover;
            padding-bottom: 15px;
        }

        .logo h1 {
            margin: 0;
        }

        .logo h1 a {
            color: #17bebb;
            font-size: 24px;
            font-weight: 700;
            font-family: 'Roboto';
        }

        /*HERO*/
        .hero {
            position: relative;
            z-index: 0;
        }

        .hero .text {
            color: rgba(0, 0, 0, .3);
        }

        .hero .text h2 {
            color: #000;
            font-size: 34px;
            margin-bottom: 0;
            font-weight: 200;
            line-height: 1.4;
        }

        .hero .text h3 {
            font-size: 24px;
            font-weight: 300;
            padding: 0;
            margin: 0;
        }

        .hero .text h2 span {
            font-weight: 650;
            color: #000;
        }

        .text-author {
            bordeR: 1px solid rgba(0, 0, 0, .05);
            max-width: 50%;
            margin: 0 auto;
            padding: 2em;
        }

        .text-author img {
            border-radius: 50%;
            padding-bottom: 20px;
        }

        .text-author h3 {
            margin-bottom: 0;
        }

        ul.social {
            padding: 0;
        }

        ul.social li {
            display: inline-block;
            margin-right: 10px;
        }

        /*FOOTER*/

        .footer {
            border-top: 1px solid rgba(0, 0, 0, .05);
            color: rgba(0, 0, 0, .5);
        }

        .footer .heading {
            color: #000;
            font-size: 20px;
        }

        .footer ul {
            margin: 0;
            padding: 0;
        }

        .footer ul li {
            list-style: none;
            margin-bottom: 10px;
        }

        .footer ul li a {
            color: rgba(0, 0, 0, 1);
        }
        .custom_link{
            font-family: Poppins, Arial, sans-serif; 
            font-size: 21px; font-weight: 700; 
            line-height: 24px; 
            background: linear-gradient(84.09deg, #AF1FA5 0.75%, #22729F 31.09%, #062564 52.96%, #022560 99.67%); color: #ffffff; 
            padding: 12px 35px; 
            border-radius: 100px;
            text-decoration: none!important; 
            min-width: 165px;
            display:inline-block;
        }
        
        
        ol.custom-counter {
            counter-reset: item;
            list-style-type: none;
            padding-left: 5px;
            text-align: left;
        }
        ol.custom-counter li {
            counter-increment: item;
            margin-bottom: 0.5em;
            font-family: Poppins;
            font-size: 18px;
            font-weight: 400;
            line-height: 31px;
            color: #333333;
        }
        ol.custom-counter li::before {
            content: counter(item) ". ";
            margin-right: 0.5em;
            width: 30px;
            display: inline-block;
            text-align: center;
        }
        @media screen and (max-width: 500px) {}

        .bg_white_w {
            background-color: #FFFFFF;
        }
    </style>



    <center style="width: 100%; background-color: #F0F0F0;font-family: 'Roboto';">
        <div style="max-width: 650px;width: 650px; margin: 0 auto;" class="email-container">
            <!-- BEGIN BODY -->
          
            <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="650" style="margin: 0 auto;">
                <tbody><tr>
                    <td valign="middle" class="bg_white_w" style="padding:40px 0 0;">
                        <table>
                            <tbody><tr>
                                <td valign="top" style="padding-top: 0px;text-align:center;">
                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                        <tbody><tr>
                                            <td style="font-family: Poppins;font-size: 33px;font-weight: 700;line-height: 42px;text-align: left;padding: 0 50px 0 20px;color: #282828;" align="center">
                                                <span style="font-family: Poppins;font-size: 33px;font-weight: 700;line-height: 42px;text-align: left;color: #1F92FD;">
                                                    <!-- [URGENT] -->
                                                </span>
                                                Activiate Staffviz Now
                                            </td>
                                        </tr>
                                    </tbody></table>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top" style="padding-top: 20px;">
                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                        <tbody><tr>
                                            <td style="text-align: center; padding-left: 20px; padding-right: 50px;" align="center">
                                                <h6 style="font-family: Poppins; font-size: 20px;line-height: 24px; text-align: left;margin-bottom: 2px;margin-top: 20px">
                                                    <span style="font-weight: 700;">Hi {{ $user->first_name }} {{ $user->last_name }},</span>
                                                </h6>
                                                <br>
                                                <p style="font-family: Poppins;font-size: 18px;font-weight: 400;line-height: 31px;text-align: left;color: #333333;margin: 0 0 10px;">
                                                    We wanted to let you know our second attempt to charge your account has failed. 
                                                    <br>
                                                    If the issue is not resolved, your user access will be blocked, and you will be limited to admin access only.  
                                                </p>
                                            </td>
                                        </tr>
                                    </tbody></table>
                                </td>
                            </tr>

                            <tr>
                                <td valign="top" style="padding: 0px;">
                                    <table role="presentation" cellspacing="0" cellpadding="20" border="0" width="100%">
                                        
                                        
                                        <tbody><tr>
                                            <td align="center">
                                                <div style="position: relative; z-index: 1;">
                                                    <a href="https://qa-app.staffviz.io/login" class="custom_link">
                                                        Access Staffviz
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center;padding-top:0;padding-bottom: 15px;" align="center">
                                                <p style="font-family: Poppins;font-size: 18px;font-weight: 400;line-height: 31px;text-align: left;color: #333333;margin: 0 0 10px;">
                                                    Please rectify this issue to avoid any disruption. If you need any assistance, our support team is here to help at <a href="mailto:support@staffviz.com" style="text-decoration: underline;color: #3A9FFD">support@staffviz.com</a>. 
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center;padding-top:0" align="center">
                                                <p style="font-family: Poppins;font-size: 17px;font-weight: 400;line-height: 24px;text-align: left;color: #333333;margin:0">
                                                    Thanks,</p>
                                                <p style="font-family: Poppins;font-size: 17px;font-weight: 700;line-height: 19px;text-align: left;color: #1F92FD;margin:0">
                                                    The StaffViz Team</p>
                                            </td>
                                        </tr>
                                    </tbody></table>
                                </td>
                            </tr>
                        </tbody></table>
                    </td>
                </tr><!-- end: tr -->
            </tbody></table>
        
        </div>
    </center>


