<?php if (! defined ( 'IN_CODE' )) die ( 'You cannot run this file directly.' );?>

  <form action="{xmb:url form}" method="post" enctype="multipart/form-data">
    <table class="xtable">
      <tr class="xrow">
        <td class="head">Upload</td>
      </tr>

      <tr class="xrow">
        <td class="alt1"><label for="upload">Upload Attachment:</label> <input type="file" id="upload" name="attachment"></td>
      </tr>
    </table>

    <div class="foot">
      <input type="submit" class="submit" value="Upload">
    </div>
  </form>
