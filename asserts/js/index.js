$(document).ready(function() { 
   const LOGGED_IN_USER_ID = $('body').data('user-id');
    /* ==========================
    Dropdown Toggle
    ========================== */
    $('#user_img_mark').on('click', function() {
        $('#profile_dropdown').toggle();
    });

    $(window).on('click', function(e) {
        if (!$(e.target).closest('#user_img_mark, #profile_dropdown').length) {
            $('#profile_dropdown').hide();
        }
    });

    /* ==========================
    Edit Profile Modal
    ========================== */
    $('#edit_profile_btn').on('click', function() {
        $('#edit_profile_modal').css('display', 'flex');
    });

    window.closeBox = function() {
        $('#edit_profile_modal').hide();
    }

    /* ==========================
    Add new post via AJAX
    ========================== */
    $('#post_btn').on('click', function() {
        const postText = $('#new_post_text').val().trim();
        if (!postText) {
            $('#error_msg').text("Post cannot be empty!");
            setTimeout(() => $('#error_msg').text(""), 1000);
            return;
        }

        $.post('add_post_ajax.php', { new_post: postText }, function(response) {
            if ($.trim(response) === "success") {
                $('#new_post_text').val('');
                loadPosts();
            } else {
                alert("Error posting!");
            }
        });
    });

    function loadPosts(profileUserId = LOGGED_IN_USER_ID) {
        $.get('fetch_posts_ajax.php', { user_id: profileUserId }, function(data) {
            $('#posts_container').html(data);
        });
    }

    /* ==========================
    Tabs functionality
    ========================== */
    function setActiveTab(tab) {
        $('.menu-link').removeClass('active');
        tab.addClass('active');
    }

    $('#tab_friends').on('click', function() {
        $('#posts_section, #about_container').hide();
        $('#friends_container').show();
        setActiveTab($(this));

        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('tab', 'friends');
        history.pushState({}, '', 'index.php?' + urlParams.toString());

        const profileUserId = urlParams.get('user_id') || LOGGED_IN_USER_ID;

        $.get('fetch_friend_list.php', { user_id: profileUserId }, function(data) {
            $('#friends_list').html(data);
        });
    });

    $('#tab_posts').on('click', function() {
        $('#friends_container, #about_container').hide();
        $('#posts_section').show();
        setActiveTab($(this));

        const params = new URLSearchParams(window.location.search);
        params.set('tab', 'posts');
        history.pushState({}, '', 'index.php?' + params.toString());

        const profileUserId = params.get('user_id') || LOGGED_IN_USER_ID;
        toggleCreatePostBox(profileUserId);
        loadPosts(profileUserId);
    });

    $('#tab_about').on('click', function() {
        $('#posts_section, #friends_container').hide();
        $('#about_container').show();
        setActiveTab($(this));

        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('tab', 'about');
        history.pushState({}, '', 'index.php?' + urlParams.toString());
    });

    /* ==========================
    Friend Search Filter
    ========================== */
    $(document).on('keyup', '.friend-search', function() {
        const val = $(this).val().toLowerCase().trim();
        let found = false;

        $('.friend-row').each(function() {
            const match = $(this).text().toLowerCase().includes(val);
            $(this).toggle(match);
            if (match) found = true;
        });

        $('#no_friends').toggle(!found);
    });

    /* ==========================
    Load Friend's Posts on Click
    ========================== */
    $('#friends_list').on('click', '.friend-row', function() {
        const friendId = $(this).data('id');
        window.location.href = "index.php?user_id=" + friendId + "&tab=posts";
    });

    function toggleCreatePostBox(profileUserId) {
        $('#create_post').toggle(profileUserId == LOGGED_IN_USER_ID);
    }

    /* ==========================
    On Page Load
    ========================== */
    const params = new URLSearchParams(window.location.search);
    const profileUserId = params.get('user_id') || LOGGED_IN_USER_ID;

    if (params.get('user_id')) loadFriendPosts(params.get('user_id'));
    toggleCreatePostBox(profileUserId);

    const tab = params.get('tab') || 'posts';
    if (tab === 'posts') $('#tab_posts').click();
    else if (tab === 'friends') $('#tab_friends').click();
    else if (tab === 'about') $('#tab_about').click();

    /* -----------------------------
    Load Friend's Posts Function
    ----------------------------- */
    function loadFriendPosts(friendId) {
        $.get('fetch_posts_ajax.php', { user_id: friendId }, function(data) {
            $('#posts_container').html(data);
        });
    }
});