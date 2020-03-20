var feedback = function(res) {
    if (res.success === true) {
        var get_link = res.data.link.replace(/^http:\/\//i, 'https://');
        document.querySelector('.status').innerHTML = '<div class="text-center mt-3 mb-3"><img class="img-thumbnail" width="20%" src=\"' + get_link + '\"/><br><br></div>';
        
        const data = new FormData();
        data.append('avatar', get_link);
        fetch('/settings', {
            method: 'POST',
            body: data
        });

        swal('','Avatar updated','success');
    }
};

new Imgur({
    clientid: '4409588f10776f7',
    callback: feedback
});