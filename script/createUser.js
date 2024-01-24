const formUser = $("#formUser");
formUser.on("submit", (e) => {
    e.preventDefault();
    const formData = formUser.serialize();
    $.post(`./api/userAPI.php?api=create`, formData, (res) => {
        if (res.state) {
            Swal.fire({
                position: "center",
                icon: "success",
                title: res.message,
                showConfirmButton: false,
                timer: 1500
            }).finally(() => {
                location.href="?page=user";
            });
        }
    });
});