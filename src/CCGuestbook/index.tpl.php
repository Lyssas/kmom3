<div class = "temp">
<h1>Guestbook Example</h1>
<p>Showing off how to implement a guestbook in Lydia. Now saving to database.</p>

<form action="<?php echo$formAction?>" method='post'>
  <p>
    <label>Message: <br/>
    <textarea name='newEntry'></textarea></label>
  </p>
  <p>
    <input type='submit' name='doAdd' value='Add message' />
    <input type='submit' name='doClear' value='Clear all messages' />
    <input type='submit' name='doCreate' value='Create database table' />
  </p>
</form>

<h2>Current messages</h2>

<?php foreach($entries as $val):?>
<div style='background-color:#f6f6f6;border:1px solid #ccc;margin-bottom:1em;padding:1em;'>
  	
	<p><?php echo htmlent($val['entry'])?></p>
	<p>At: <?php echo $val['created']?></p>
 
</div>
<?php endforeach;?>
</div>
