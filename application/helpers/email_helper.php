<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('html_email_template')) {
	function html_email_template($body = '', $link = null, $signature = null)
	{
		$htmlContent = `<!DOCTYPE html
		PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
		xmlns:o="urn:schemas-microsoft-com:office:office">
	
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<!--[if !mso]><!-->
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<!--<![endif]-->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="format-detection" content="telephone=no">
		<meta name="x-apple-disable-message-reformatting">
		<title></title>
	</head>
	
	<body
		style="width: 100% !important; margin: 0; padding: 0; mso-line-height-rule: exactly; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; background-color: #f4f4f4"
		class="">
		<table class="pc-email-body" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation"
			style="table-layout: fixed;">
			<tbody>
				<tr>
					<td class="pc-email-body-inner" align="center" valign="top">
						<table class="pc-email-container" width="100%" align="center" border="0" cellpadding="0" cellspacing="0"
							role="presentation" style="margin: 0 auto; max-width: 620px;">
							<tbody>
								<tr>
									<td align="left" valign="top" style="padding: 0 10px;">
										<table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
											<tbody>
												<tr>
													<td height="20" style="font-size: 1px; line-height: 1px;">&nbsp;</td>
												</tr>
											</tbody>
										</table>
										<!-- BEGIN MODULE: Menu 9 -->
										<table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
											<tbody>
												<tr>
													<td valign="top" bgcolor="#ffffff"
														style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1)">
														<table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
															<tbody>
																<tr>
																	<td class="pc-sm-p-30 pc-xs-p-25-20" align="center" valign="top"
																		style="padding: 10px 10px;">
																		<h1 style="font-size: 45px;">
																			<a href="" style="text-decoration: none;">SurveyMonkey</a>
																		</h1>
																	</td>
																</tr>
															</tbody>
															<tbody>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
										<!-- END MODULE: Menu 9 -->
										<!-- BEGIN MODULE: Header 5 -->
										<table width="100%" border="0" cellspacing="0" cellpadding="0" role="presentation">
											<tbody>
												<tr>
													<td height="8" style="font-size: 1px; line-height: 1px;">&nbsp;</td>
												</tr>
											</tbody>
										</table>
										<table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
											<tbody>
												<tr>
													<td class="pc-sm-p-20-25-35 pc-xs-p-15" valign="top" bgcolor="#ffffff"
														style="padding: 20px 35px 35px 35px; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1)">
														<table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
															<tbody>
																<tr>
																	<td height="18" style="font-size: 1px; line-height: 1px;">&nbsp;</td>
																</tr>
															</tbody>
															<!--<tbody>                               <tr>                                 <td valign="top" align="center" style="padding: 0 5px;">                                   <a href="https://www.swagbucks.com/?cmd=ac-email-refer&h=025612faeba57ac792146f23a7ba5d9f&invid=39111740&cmp=197&cxid=1100-Email&aff_sid=invite&p=PADDING" style="text-decoration: none;"><img src="https://uassets.prdg.io/EmailCreatives/Twogirlsonphone.jpg" width="520" height="" alt="" style="border: 0; line-height: 100%; outline: 0; -ms-interpolation-mode: bicubic; border-radius: 6px; max-width: 100%; height: auto; display: block; Margin: 0 auto; color: #1B1B1B;"></a>                                 </td>                               </tr>                               <tr>                                 <td height="21" style="font-size: 1px; line-height: 1px;">&nbsp;</td>                               </tr>-->
															<tbody>
																<tr>
																	<td class="pc-xs-fs-30 pc-xs-lh-42 pc-fb-font"
																		style="padding: 0 5px; font-family: 'Open Sans', Helvetica, Arial, sans-serif; font-size: 36px; font-weight: 800; line-height: 46px; letter-spacing: -0.6px; color: #1B1B1B; text-align: center;"
																		valign="top">Hi,</td>
																</tr>
																<tr>
																	<td height="20" style="font-size: 1px; line-height: 20px;">&nbsp;</td>
																</tr>
															</tbody>
															<tbody>
																<tr>
																	<td class="pc-fb-font" valign="top"
																		style="padding: 0 5px; font-family: 'Open Sans', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 300; line-height: 28px; letter-spacing: -0.2px; color: #9B9B9B; text-align: center;">
																		` . $body . `
																	</td>
																</tr>
																<tr>
																	<td height="25" style="line-height: 1px; font-size: 1px;">&nbsp;</td>
																</tr>
															</tbody>`;
		if ($link != null) {
			$htmlContent .= `<tbody>
																<tr>
																	<td style="padding: 5px;" valign="top" align="center">
																		<table border="0" cellpadding="0" cellspacing="0" role="presentation">
																			<tbody>
																				<tr>
																					<td style="padding: 13px 17px; border-radius: 8px; background-color: #69b8d6;"
																						bgcolor="#69b8d6" valign="top" align="center">
																						` . $link . `
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																</tr>
															</tbody>`;
		}

		if ($signature != null) {
			$htmlContent .= `<tbody>
																<tr>
																	<td height="25" style="line-height: 1px; font-size: 1px;">&nbsp;</td>
																</tr>
																<tr>
																	<td class="pc-fb-font" valign="top"
																		style="padding: 0 5px; font-family: 'Open Sans', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 300; line-height: 28px; letter-spacing: -0.2px; color: #9B9B9B; text-align: center;">
																		<div style="text-align: left; color: #666666;">
																			- ` . $signature . `
																		</div>
																	</td>
																</tr>
																<tr>
																	<td height="25" style="line-height: 1px; font-size: 1px;">&nbsp;</td>
																</tr>
															</tbody>`;
		}

		$htmlContent .= `</table>
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
		<!-- Fix for Gmail on iOS -->
		<div class="pc-gmail-fix" style="white-space: nowrap; font: 15px courier; line-height: 0;">&nbsp; &nbsp; &nbsp; &nbsp;
			&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
			&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </div>
	</body>
	
	</html>`;

		return $htmlContent;
	}
}
