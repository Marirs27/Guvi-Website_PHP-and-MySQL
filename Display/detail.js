$("#editIcon").click( function () {
  $("#displayProfile").addClass("hideSection");
  $("#updateProfile").removeClass("hideSection");
  $(".editBttn").addClass("whiten");
  $(".overlay").addClass("centre");
  $("h1").text(" Edit Profile ");
  $("#profilePic").addClass("d-none");
  $("#editPic").removeClass("d-none");
});

$(document).ready(function() {
    $.ajax({
        url: "Display/display.php" ,
        success:function(d) {
            var obj = JSON.parse(d);
            // For display page :-
            $("#nameProfileValue span").text(obj["firstName"]) ;
            $("#nameProfileValue span").append(" ") ;
            $("#nameProfileValue span").append(obj["lastName"]) ;
            $("#numberProfileValue span").text(obj["phnNumber"]) ;
            $("#mailProfileValue span").text(obj["email"]) ;
            // For Update Page :-
            $("#name").val(obj["firstName"]) ;
            $("#lastName").val(obj["lastName"]) ;
            $("#mobileNumber").val(obj["phnNumber"]) ;
            $("#email").val(obj["email"]) ;
            $("#password").val(obj["password"]) ;
        }    
    });
});

$(document).ready(function() {
    $("#updateSaveButton").click( function(e) {
        e.preventDefault() ;
        $.ajax({
            url: "Display/display.php" ,
            type: "post" ,
            data:$("#updateForm").serialize() ,
            success:function(d) {
                window.location.replace("details.html") ;
            }    
        });
    });   
});