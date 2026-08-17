<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Automation Job Failed</title>
</head>
<body style="margin: 0; padding: 0; background-color: #F8FAFC; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1E293B; -webkit-font-smoothing: antialiased;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #F8FAFC; padding: 40px 15px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 600px; background-color: #FFFFFF; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05); border: 1px solid #E2E8F0;">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%); padding: 32px 36px; text-align: left;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td>
                                        <div style="display: inline-flex; align-items: center;">
                                            <span style="font-size: 22px; font-weight: 800; color: #FFFFFF; letter-spacing: -0.5px;">Auto<span style="color: #22C55E;">flow</span></span>
                                            <span style="background: rgba(239, 68, 68, 0.15); color: #F87171; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 6px; margin-left: 10px; text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid rgba(239, 68, 68, 0.3);">Alert</span>
                                        </div>
                                    </td>
                                    <td align="right">
                                        <span style="background: #EF4444; color: #FFFFFF; font-size: 11px; font-weight: 700; padding: 5px 12px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px; display: inline-block;">
                                            ✕ Failed
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 36px;">
                            <h1 style="margin: 0 0 12px 0; font-size: 20px; font-weight: 700; color: #991B1B; line-height: 1.3;">
                                Automation Workflow Encountered an Issue ⚠️
                            </h1>
                            <p style="margin: 0 0 24px 0; font-size: 14px; color: #64748B; line-height: 1.6;">
                                An error occurred while executing the content automation pipeline for <strong>{{ $websiteName }}</strong>. The system halted execution to ensure your production repository remains safe.
                            </p>

                            <!-- Error Message Box -->
                            <div style="background-color: #FEF2F2; border: 1px solid #FCA5A5; border-radius: 10px; padding: 16px; margin-bottom: 24px;">
                                <div style="font-size: 11px; font-weight: 700; color: #991B1B; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">
                                    Failure Reason
                                </div>
                                <div style="font-family: monospace; font-size: 13px; color: #7F1D1D; word-break: break-word;">
                                    {{ $errorMessage }}
                                </div>
                            </div>

                            <!-- Details Card -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #F8FAFC; border-radius: 12px; border: 1px solid #E2E8F0; margin-bottom: 28px;">
                                <tr>
                                    <td style="padding: 16px 20px; border-bottom: 1px solid #EDF2F7;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td width="40%" style="font-size: 13px; color: #64748B; font-weight: 500;">Website Target</td>
                                                <td width="60%" style="font-size: 13px; color: #0F172A; font-weight: 600; text-align: right;">{{ $websiteName }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 16px 20px; border-bottom: 1px solid #EDF2F7;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td width="40%" style="font-size: 13px; color: #64748B; font-weight: 500;">Target Page</td>
                                                <td width="60%" style="font-size: 13px; color: #0F172A; font-weight: 600; text-align: right; font-family: monospace; background-color: #EEF2F6; padding: 2px 6px; border-radius: 4px;">{{ $pagePath }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 16px 20px;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td width="40%" style="font-size: 13px; color: #64748B; font-weight: 500;">Job ID</td>
                                                <td width="60%" style="font-size: 13px; color: #0F172A; font-weight: 600; text-align: right;">#{{ $jobId }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Call to Action Button -->
                            <div style="text-align: center; margin-bottom: 28px;">
                                <a href="{{ $actionUrl ?? url('/jobs') }}" style="display: inline-block; background: #DC2626; color: #FFFFFF; font-size: 14px; font-weight: 600; text-decoration: none; padding: 13px 32px; border-radius: 10px; box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);">
                                    Inspect Job & Retry in Dashboard ➔
                                </a>
                            </div>

                            <p style="margin: 0; font-size: 12px; color: #94A3B8; text-align: center; line-height: 1.5;">
                                Please check your AI API key balance or GitHub token permissions.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #F1F5F9; padding: 24px 36px; text-align: center; border-top: 1px solid #E2E8F0;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #475569;">
                                Autoflow • AI Content Automation Platform
                            </p>
                            <p style="margin: 0; font-size: 11px; color: #94A3B8;">
                                Sent automatically according to your notification preferences in Settings.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
