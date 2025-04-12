<!DOCTYPE html>

<html lang="en" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">

<head>
    <title></title>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <!--[if mso]>
    <xml>
    <o:OfficeDocumentSettings>
        <o:PixelsPerInch>96</o:PixelsPerInch>
        <o:AllowPNG/>
    </o:OfficeDocumentSettings>
    </xml><![endif]-->
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
        }

        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: inherit !important;
        }

        #MessageViewBody a {
            color: inherit;
            text-decoration: none;
        }

        p {
            line-height: inherit
        }

        @media (max-width: 520px) {
            .icons-inner {
                text-align: center;
            }

            .icons-inner td {
                margin: 0 auto;
            }

            .row-content {
                width: 100% !important;
            }

            .image_block img.big {
                width: auto !important;
            }

            .column .border {
                display: none;
            }

            .stack .column {
                width: 100%;
                display: block;
            }
        }
    </style>
</head>

<body style="background-color: #FFFFFF; margin: 0; padding: 0; -webkit-text-size-adjust: none; text-size-adjust: none;">
    <table border="0" cellpadding="0" cellspacing="0" class="nl-container" role="presentation"
        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #FFFFFF; border-spacing: 10px 5px;" width="100%">
        <tbody>

            <tr>
                <td>
                    <h3>Hi,</h3>
                </td>
            </tr>
            <tr>
                <td>
                    <p>This is to inform you that a new company has been created on StaffViz with the following details:</p>
                </td>
            </tr>


            <tr>
                <td>
                    <ul>
                        <li><b>User Name:</b> {{ $user->first_name }} {{ $user->last_name }}</li>
                        <li><b>User Email:</b> {{ $user->email }} </li>
                        <li><b>Company Name:</b> {{ $company->title }}</li>
                        <li><b>Company Initials:</b> {{ $company->company_initial }}</li>
                        <li><b>Creation Date:</b> {{ $company->created_at }}</li>
                        <li><b>IP Address:</b> {{ $user->ip_address }}</li>
                        <li><b>Ip location:</b> {{ $user->ip_location }}</li>
                    </ul>
                </td>
            </tr>

            <tr>
                <td>
                    <p>For additional information, feel free to reach out to us at <a
                            href="mailto:support@staffviz.com">support@staffviz.com</a>.</p>
                </td>
            </tr>

            <tr>
                <td>Best regards,</td>
            </tr>
            <tr>
                <td>Team StaffViz</td>
            </tr>
        </tbody>
    </table>
</body>

</html>