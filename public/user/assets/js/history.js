$(document).ready(function() {
    $('.today-btn').click(function() {
        $.ajax({
            url: '/user/history/generate_for_today',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    alert('You have successfully generated your today trip report')
                } else {
                    console.error('Error modifying entity:', response.message);
                }
            },
            error: function(error) {
                console.error('Error:', error, error.message);
            }
        });
    })

    $('.week-btn').click(function() {
        $.ajax({
            url: '/user/history/generate_for_week',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    alert('You have successfully generated your weekly trip report')
                } else {
                    console.error('Error modifying entity:', response.message);
                }
            },
            error: function(error) {
                console.error('Error:', error, error.message);
            }
        });
    })

    $('.month-btn').click(function() {
        $.ajax({
            url: '/user/history/generate_for_month',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    alert('You have successfully generated your monthly trip report')
                } else {
                    console.error('Error modifying entity:', response.message);
                }
            },
            error: function(error) {
                console.error('Error:', error, error.message);
            }
        });
    })

    $('.all-btn').click(function() {
        $.ajax({
            url: '/user/history/generate_for_all',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    alert('You have successfully generated all your trip report')
                } else {
                    console.error('Error modifying entity:', response.message);
                }
            },
            error: function(error) {
                console.error('Error:', error, error.message);
            }
        });
    })
});
