$(document).ready(function () {
    // show modal add
    $("#showModalAdd").click(function () { 
        $("#modalAdd").addClass("flex").removeClass("hidden");
    });
    // hide modal add
    $("#hideModalAdd").click(function () { 
        $("#modalAdd").addClass("hidden").removeClass("flex");
    });
    // add list
    $("#addList").click(function (e) { 
        e.preventDefault();
        var todoName = $("input[name=todoName]").val();
        $.ajax({
            type: "POST",
            url: "{{ route('add') }}",
            data: {todoName:todoName},
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            success: function (response) {
                console.log(response)
                if(response.status == 200) {
                    // reset error messages
                    $("#errorTodoName").html("");
                    // hide modal and reset todo name input value
                    $("#modalAdd").addClass("hidden").removeClass("flex");
                    $("input[name=todoName]").val("");
                    Toastify({
                        text: "Success",
                        className: "info",
                        style: {
                            background: "#22c55e",
                        }
                    }).showToast();
                    $("#myList").append(
                        `<tr>
                            <td>
                                <div class="badge badge-success">New</div>
                                ${response.list.todoName}
                            </td>
                            <td>
                                <div class="w-full flex gap-2">
                                    <a href="" class="btn btn-xs">edit</a>
                                    <a href="" class="btn btn-xs">delete</a>
                                </div>
                            </td>
                        </tr>`
                    );
                } else {
                    Toastify({
                        text: "Failed",
                        className: "info",
                        style: {
                            background: "#ef4444",
                        }
                    }).showToast();
                    $("#errorTodoName").html(response.errorMessages.todoName);
                }
            }
        });
    });
});