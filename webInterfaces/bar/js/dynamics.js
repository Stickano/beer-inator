
$(document).ready(function(){

    // Close error messages
    $(".closeErr").click(function(){
        $(".errorContainer").slideToggle(200);
    });

    // Refresh the page (6sec)
    setTimeout(location.reload.bind(location), 30000);
});
