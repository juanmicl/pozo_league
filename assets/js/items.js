function unlock(item_id) {
  fetch(`/api/items/unlock/${item_id}`)
    .then(function(response) {
        return response.json();
    })
    .then(function(data) {
        if (data.status == 200) {
            //swal('',data.msg,'success');
            location.reload();
        } else if (data.status == 400) {
            swal('',data.msg,'warning');
        } else {
            swal('',data.msg,'error');
        }
      //console.log(data.status);
    });
}
