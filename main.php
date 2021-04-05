
<h2>Hallo, Do you want to proceed?</h2>
<form action="proceed.php" method="POST">
    <button type="submit">YES<button>
</form>

<form action="cancel.php" method="POST">
    <button type="submit">CANCEL<button>
</form>


<?php
/*

//$vsaf='y';

//checking if the customer is ready to proceed to the vsaf capability
echo "Hallo, Enter Y if you want to proceed or any other letter to cancel the vsaf prompt.";
//fscanf(STDIN,"%c",$choice);

if ($choice==$vsaf) 
{
	echo "Enter Your Phone Number:";
	fscanf(STDIN,"%lu\n",$phone); //lu is for int unsigned long
	echo "Enter Id number used to register your Phone Number:";
	fscanf(STDIN,"%lu\n",$idNumber);

	//Storing $phone and $idnumber in an array
	$credentials=array("customerPhone"=>$phone,"customerIdNumber"=>$idNumber);

//Checking if the credentials match with any record from the customers present in the database


} 

else 
{
	echo "You cancelled successfully. Safaricom the better option,Thankyou.";

	//call a function to return the customer to the normal mpesa dashboard because he/she is not ready to access vsaf


}
*/

