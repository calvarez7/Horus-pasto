<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Sumimedical</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body style="margin: 0; padding: 0;">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">	
		<tr>
			<td style="padding: 10px 0 30px 0;">
				<table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border: 1px solid #cccccc; border-collapse: collapse;">
					<tr>
						<td align="center" bgcolor="#fff" style="padding: 40px 0 30px 0; color: #153643; font-size: 28px; font-weight: bold; font-family: Arial, sans-serif;">
							<img src="{{url('/images/logo.png')}}" alt="" width="300" height="230" style="display: block;" />
						</td>
					</tr>
					<tr>
						<td bgcolor="#ffffff" style="padding: 40px 30px 40px 30px;">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td style="color: #153643; font-family: Arial, sans-serif; font-size: 24px;">
										<b>Solicitud</b>
									</td>
								</tr>
								<tr>
									@if($tipo == 'asignar')
										<td style="padding: 20px 0 30px 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
											<p>Buen día.</p>
											<p>Se le ha <strong>asignado</strong> una solicitud con radicado N• {{ $radicado_id }}.</p>
											<p>Recuerde darle solucion lo más pronto posible.</p>
											<p>Para ir a la solicitud click en el siguiente enlace <a href="http://www.horus-health.com/solicitudes/asignadas">Horus Health</a>.</p>
										</td>
									@elseif($tipo == 'comentario')
										<td style="padding: 20px 0 30px 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
											<p>Buen día.</p>
											<p>Se le ha <strong>comentado</strong> una solicitud con radicado N• {{ $radicado_id }}.</p>
											<p>Recuerde darle solucion lo más pronto posible.</p>
											<p>Para ir a la solicitud click en el siguiente enlace <a href="http://www.horus-health.com/solicitudes/asignadas">Horus Health</a>.</p>
										</td>
									@endif
								</tr>
								<tr>
									<td>
										<table border="0" cellpadding="0" cellspacing="0" width="100%">
											<tr>
												<td width="260" valign="top">
													<table border="0" cellpadding="0" cellspacing="0" width="100%">
														<tr>
														</tr>
													</table>
												</td>
												<td style="font-size: 0; line-height: 0;" width="20">
													&nbsp;
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td bgcolor="#28b463" style="padding: 30px 30px 30px 30px;">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td style="color: #ffffff; font-family: Arial, sans-serif; font-size: 14px;" width="75%">
										&reg; Sumimedical 2021<br/>
										<a href="https://sumimedical.com/" style="color: #ffffff;">Sumimedical.com<font color="#ffffff"></font></a>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>