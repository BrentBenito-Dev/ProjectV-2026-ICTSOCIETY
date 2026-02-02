<h2>Add a Confession</h2>
<form action="add-confession.php" method="POST">
    <label>First Name:</label>
    <input type="text" name="first_name" required>

    <label>Last Name:</label>
    <input type="text" name="last_name" required>

    <label>Custom Clue:</label>
    <input type="text" name="custom_clue">

    <label>Message (Max 100):</label>
    <textarea name="message" maxlength="100"></textarea>

    <label>Recipient Name:</label>
    <input type="text" name="recipient_name">

    <button type="submit">Save to Database</button>
</form>