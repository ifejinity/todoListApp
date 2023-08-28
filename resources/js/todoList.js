$(document).ready(function () {
    // class request
    class Request {
        // addition of todo
        add(todoName) {
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
                            text: "Add success",
                            className: "info",
                            style: {
                                background: "#22c55e",
                            }
                        }).showToast();
                        $("#myList").append(
                            `<tr>
                                <td>
                                    ${response.list.todoName}
                                </td>
                                <td>
                                    ${response.list.created_at}
                                </td>
                                <td>
                                    <form class="w-full flex gap-2">
                                        <button type="submit" value="${response.list.id}" class="btn btn-xs btn-info btn-active edit">edit</button>
                                        <button type="submit" value="${response.list.id}" class="btn btn-xs btn-error btn-active delete">delete</button>
                                    </form>
                                </td>
                            </tr>`
                        );
                    } else {
                        Toastify({
                            text: "Add failed",
                            className: "info",
                            style: {
                                background: "#ef4444",
                            }
                        }).showToast();
                        $("#errorTodoName").html(response.errorMessages.todoName);
                    }
                }
            });
        }
        // deletion of todo
        delete(id) {
            $.ajax({
                type: "POST",
                url: "{{ route('delete') }}",
                data: {_method:"delete", id:id},
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function (response) {
                    console.log(response);
                    if(response.status == 200) {
                        $("#myList").html("");
                        for(let x = 0; x < response.list.length; x++) {
                            $("#myList").append(
                                `<tr>
                                    <td>
                                        ${response.list[x].todoName}
                                    </td>
                                    <td>
                                        ${response.list[x].created_at}
                                    </td>
                                    <td>
                                        <form class="w-full flex gap-2">
                                            <button type="submit" value="${response.list[x].id}" class="btn btn-xs btn-info btn-active edit">edit</button>
                                            <button type="submit" value="${response.list[x].id}" class="btn btn-xs btn-error btn-active delete">delete</button>
                                        </form>
                                    </td>
                                </tr>`
                            );
                        }
                        Toastify({
                            text: "Delete success",
                            className: "info",
                            style: {
                                background: "#22c55e",
                            }
                        }).showToast();
                    } else {
                        Toastify({
                            text: "Delete failed",
                            className: "info",
                            style: {
                                background: "#ef4444",
                            }
                        }).showToast();
                    }
                }
            });
        }
    }
    // instanciation of class request
    const request = new Request();
    let myList = document.querySelector("#myList");
    myList.addEventListener('click', (event) => {
        event.preventDefault();
        if (event.target.classList.contains("delete")) {
            const id = event.target.value;
            request.delete(id);
        }
    });

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
        request.add(todoName)
    });
});