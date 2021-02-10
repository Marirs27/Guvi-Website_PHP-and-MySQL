$("#signUpButton").on("pointerdown", function () {
    $("#signIn").addClass("hideSection");
    $("#signUp").removeClass("hideSection");
});

$(document).ready(function() {
    $("#signUpSubmit").click( function () {
        $.ajax({
            url: "Index/style.php" ,
            type: "post" ,
            data:$("#signUpForm").serialize() ,
            success:function(d) {
                alert(d) ;
            }    
        });
    });
});

$(document).ready(function() {
    $("#signInSubmit").click( function (e) {
        e.preventDefault() ;
        $.ajax({
            url: "Index/style.php" ,
            type: "post" ,
            data:$("#signInForm").serialize() ,
            success:function(d) {
                var obj = JSON.parse(d);
                if (obj["signInCheck"] == "allowSignIn") {
                    window.location.replace("details.html") ;
                } else {
                    $("#alertBig").addClass(obj["errorCheck"]) ;
                    $("#alertSmall").addClass(obj["errorCheck"]) ;
                    $("#alertBig span").text(obj["errorMsg"]) ;
                    $("#alertSmall span").text(obj["errorMsg"]) ;
                    $("#bigClose").click( function () {
                        $("#alertBig").removeClass(obj["errorCheck"]) ;
                    });
                    $("#smallClose").click( function () {
                        $("#alertSmall").removeClass(obj["errorCheck"]) ;
                    });
                }
            }    
        });
    });
});