// hub operation module common functions

//checks if big_date is greater or equal to small_date
function comparedate(big_date, small_date){
	var bigdate = new Date(big_date).setHours(0,0,0,0);
	var smalldate = new Date(small_date).setHours(0,0,0,0);
	if (bigdate.valueOf()>= smalldate.valueOf()){
		return true;
	}else{
		return false;
	}
}