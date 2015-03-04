/**
 * CHANGE COMPLETE STATE
 * RELOAD TASK LIST AND TASK LIST COMPLETED PART
 * @param id
 */
function changeTaskComplete(id){
    $.ajax({
        type: "POST",
        url: Routing.generate('dimando_todo_list_change_complete', { "id": id }),
        success: function (json) {
            if(!json.error)
            {
                $('.task_list').empty().html(json.tasks);
                $('.task_list_complete').empty().html(json.tasksComplete);
            }
        },
        error: function (data) {
            console.log("error ajax");
        }
    });
}

/**
 * ADD TASK
 * RELOAD TASK LIST PART
 */
function addTask(){

    var message = $('.statetaskform');

    $.ajax({
        type: "POST",
        url: Routing.generate('dimando_todo_list_create_ajax'),
        data: $('.taskForm').serialize(),
        dataType: 'json',
        success: function (json)
        {
            if(json.error)
            {
                message.addClass("alert alert-danger").empty().html(json.error);
            }
            else
            {
                message.removeClass("alert-danger").addClass("alert alert-success").empty().html(json.success);
                $('.task_list').empty().html(json.tasks)
            }
        },
        error: function (data) {
            console.log("error ajax");
        }
    });
}
