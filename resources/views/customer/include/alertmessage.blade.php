<script>
    function alertMessage() {
        function error(message) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: message
            })
        }

        function confirm(message, deleteitem) {

            Swal.fire({
                title: 'Are you sure?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteitem();
                    Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    )

                }
            })
        }

        function formalConfirm(message, deleteitem) {

            Swal.fire({
                title: 'Are you sure?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteitem();
                    Swal.fire(
                        'Update!',
                        'Status Update SuccessFully.',
                        'success'
                    )

                }
            })
        }

        alertMessage.error = error;
        alertMessage.confirm = confirm;
        alertMessage.formalConfirm = formalConfirm;
    }

    alertMessage();

    $(function() {
        'use strict';
        $(document).on('click', '.switchUrl', function() {
            let status = $(this).attr('url');
            var table = $('#server_side_lode').DataTable();
            let url =
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to change status?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-outline-danger ml-1'
                    },
                    buttonsStyling: false
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            url: status,
                            type: 'GET',
                            success: function(data) {
                                console.log(data);
                                if (data.code == 203) {
                                    Swal.fire(
                                        'Warning!',
                                        'Your status id must be numeric.',
                                        'success'
                                    )
                                } else if (data.code == 404) {
                                    Swal.fire(
                                        'Warning!',
                                        'Your status info not found.',
                                        'warning'
                                    )
                                } else {
                                    Toast.fire({
                                        icon: 'success',
                                        title: 'Your data successfully deleted!!'
                                    })
                                }
                                // Swal.fire(
                                //     'Deleted!',
                                //     'Your file has been deleted.',
                                //     'success'
                                // )

                            },
                            error: function(data) {
                                alert(data.responseText);
                            }
                        });
                        Swal.fire({
                            icon: 'success',
                            title: 'Status Updated',
                            text: 'Status Updated Successfully!',
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        });
                        table.ajax.reload();
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire({
                            title: 'Cancelled',
                            // text: 'Status Not Updated :)',
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        });
                        table.ajax.reload();
                    }
                });
        });


    });

    function sweetalert() {

        $(document).on('click', '.switchUrl', function() {
            let status = $(this).attr('url');
            var table = $('#server_side_lode').DataTable();
            let url =
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to change status?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-outline-danger ml-1'
                    },
                    buttonsStyling: false
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            url: status,
                            type: 'GET',
                            success: function(data) {
                                console.log(data);
                                if (data.code == 203) {
                                    Swal.fire(
                                        'Warning!',
                                        'Your status id must be numeric.',
                                        'success'
                                    )
                                } else if (data.code == 404) {
                                    Swal.fire(
                                        'Warning!',
                                        'Your status info not found.',
                                        'warning'
                                    )
                                } else {
                                    Toast.fire({
                                        icon: 'success',
                                        title: 'Your data successfully deleted!!'
                                    })
                                }
                                // Swal.fire(
                                //     'Deleted!',
                                //     'Your file has been deleted.',
                                //     'success'
                                // )

                            },
                            error: function(data) {
                                alert(data.responseText);
                            }
                        });
                        Swal.fire({
                            icon: 'success',
                            title: 'Status Updated',
                            text: 'Status Updated Successfully!',
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        });
                        table.ajax.reload();
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire({
                            title: 'Cancelled',
                            // text: 'Status Not Updated :)',
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        });
                        table.ajax.reload();
                    }
                });
        });

        function error($message) {
            Swal.fire({
                title: 'Error!',
                text: $message,
                icon: 'error',
                customClass: {
                    confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false
            });
        }
        sweetalert.error = error;
    }
    sweetalert();
</script>
