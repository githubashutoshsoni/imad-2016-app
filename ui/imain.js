
function loadCommentForm () {
    var commentFormHtml = `
        <h5>Submit a comment</h5>
        <textarea id="comment_text" rows="5" cols="100" placeholder="Enter your comment here..."></textarea>
        <br/>
        <input type="submit" id="submit" value="Submit" />
        <br/>
        `;
    document.getElementById('comment_form').innerHTML = commentFormHtml;

    // Submit username/password to login
    var submit = document.getElementById('submit');
    submit.onclick = function () {
        // Create a request object
        var request = new XMLHttpRequest();

        // Capture the response and store it in a variable
        request.onreadystatechange = function () {
          if (request.readyState === XMLHttpRequest.DONE) {
                // Take some action
                if (request.status === 200) {
                    // clear the form & reload all the comments
                    document.getElementById('comment_text').value = '';
                } else {
                    alert('Error! Could not submit comment');
                }
                submit.value = 'Submit';
          }
        };

        // Make the request
        var comment = document.getElementById('comment_text').value;
        request.open('POST', '/submit-comment-profile/', true);
        request.setRequestHeader('Content-Type', 'application/json');
        request.send(JSON.stringify({comment: comment}));
        submit.value = 'Submitting...';

    };
}

function loadLogin () {
    // Check if the user is already logged in
    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState === XMLHttpRequest.DONE) {
            if (request.status === 200) {
                loadCommentForm(this.responseText);
            }
        }
    };

    request.open('GET', '/check-login', true);
    request.send(null);
}
function loadnav(){
  document.getElementById('nav-bar').innerHTML=`
  <nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="/">Blogging</a>
      </div>
      <ul class="nav navbar-nav">
        <li class="active"><a href="/">Home</a></li>
        <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="/ui/Introduction">About me <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">profile</a></li>
            <li><a href="#">Contact</a></li>
          </ul>
        </li>
        <li><a href="#">Page 2</a></li>
        <li><a href="#">Page 3</a></li>
      </ul>
    </div>
  </nav>
`
return;
}
function loadfooter(){
  document.getElementById('foot').innerHTML=`
        <div class="container row-footer ";>
            <div class="row row-footer">
	        <div class="col-xs-5 col-xs-offset-1 col-sm-2 col-sm-offset-1">
                    <h5>Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.html">Home</a></li>
                        <li><a href="/ui/Introduction">About me</li>

                        <li><a href="index.html#">Contact</a></li>
                    </ul>
                </div>
	        <div class="col-xs-6 col-sm-5">
                    <h5>I live in</h5>
                    <Address>
		              <br>Chennai
                   <i class="fa fa-phone"></i>: 9840523023<br>
		              <i class="fa fa-envelope"></i>: <a href="githubashutoshsoni@hasura-app.io">/</a>
		    </address>
                </div>
		<div class="col-xs-12 col-sm-4">
                    <div class="nav navbar-nav">
                    <a href="https://www.facebook.com/bootsnipp"><i id="social-fb" class="fa fa-facebook-square fa-3x social"></i></a>
	            <a href="https://twitter.com/bootsnipp"><i id="social-tw" class="fa fa-twitter-square fa-3x social"></i></a>
	            </div>
                </div>
                <div class="col-xs-12">
                    <p style="padding:10px;"></p>
                    <p align="center">� Copyright 2015 Ristorante Con Fusion</p>
                </div>
            </div>
        </div>`
return;
}
loadnav();
loadfooter();
loadLogin();
loadComments();
