<!DOCTYPE html>
<html>
<head>

<style>

</style>
<script src="ext_lib/js-lib/jquery-3.2.1.js"></script>
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>
<body>
    <div  id="detail_cont" class="card">
        <div class="card-body">
           <div>
           <button id="go_back_but" class="btn btn-primary hBack" type="button">&larr;</button>
           <p>
            Price of the item is Rs 20
           </p>
           <button id="paypal_butt" class="btn btn-primary">Pay Via PayPal</button>
           </div>
        </div>
    </div>
<script>
$(".hBack").on("click", function(e){
    e.preventDefault();
    window.history.back();
});
</script>
</body>

</html>