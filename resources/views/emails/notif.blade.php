<html>
  <head>
    <title>{{ $data->subject }}</title>
  </head>
  <body>
    <table style="width:100%;">
      <tr>
        <td style="padding: 10px;">
          <h1 style="color: #1c75bb;">{{ $data->subject }}</h1>
        </td>
      </tr>
      <tr>
        <td style="padding: 10px;">
          {{ $data->body }}
        </td>
      </tr>
      <tr>
        <td style="padding: 10px;">
          <p style="font-size: 12px;">This is an automated email, please do not reply.</p>
        </td>
      </tr>
    </table>
  </body>
</html>
