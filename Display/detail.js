$("#editIcon").on("pointerdown", function () {
  $("#displayProfile").addClass("hideSection");
  $("#updateProfile").removeClass("hideSection");
  $(".editBttn").addClass("whiten");
  $(".overlay").addClass("centre");
  $("h1").text(" Edit Profile ");
  $("#profilePic").addClass("d-none");
  $("#editPic").removeClass("d-none");
});


/* $("#updateSave").on("pointerdown", function () {
  $("#displayProfile").removeClass("hideSection");
  $("#updateProfile").addClass("hideSection");
  $(".editBttn").removeClass("whiten");
  $(".overlay").removeClass("centre");
  $("h1").text(" Profile ");
  $("#editPic").addClass("d-none");
  $("#profilePic").removeClass("d-none");
}); */
