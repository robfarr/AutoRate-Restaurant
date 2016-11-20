<?php

/* @var $this yii\web\View */

$this->title = 'Rate a Restaurant &middot; Rate your restaurant meal by uploading a selfie.';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Restaurant Name</h1>

        <!-- Display most popular aggregate emotion -->
        <p>The average user is mainly excited about this restaurant.</p>

        <!-- Show aggregate bar here -->
        <div class="progress">
            <div class="progress-bar progress-bar-success" role="progressbar" style="width: 32%">
                <span>32% Excited</span>
            </div>
            <div class="progress-bar progress-bar-danger" role="progressbar" style="width: 12%">
                <span>12% Unhappy</span>
            </div>
        </div>

    </div>

    <div class="body-content">

        <div class="row">

            <!-- Loop and display each rating image & emotion bars -->
            <table class="table table-hover">
                <tbody>

                    <tr>
                        <th width="100px"><img src="https://placeholdit.imgix.net/~text?txtsize=33&w=150&h=150"
                                                       alt="Upload"
                                 class="img-thumbnail"></th>
                        <td style="vertical-align: middle">
                            <div class="progress">
                                <div class="progress-bar progress-bar-success" role="progressbar" style="width: 32%">
                                    <span>32% Excited</span>
                                </div>
                                <div class="progress-bar progress-bar-danger" role="progressbar" style="width: 12%">
                                    <span>12% Unhappy</span>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th width="100px"><img src="https://placeholdit.imgix.net/~text?txtsize=33&w=150&h=150"
                                               alt="Upload"
                                               class="img-thumbnail"></th>
                        <td style="vertical-align: middle">
                            <div class="progress">
                                <div class="progress-bar progress-bar-success" role="progressbar" style="width: 32%">
                                    <span>32% Excited</span>
                                </div>
                                <div class="progress-bar progress-bar-danger" role="progressbar" style="width: 12%">
                                    <span>12% Unhappy</span>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th width="100px"><img src="https://placeholdit.imgix.net/~text?txtsize=33&w=150&h=150"
                                               alt="Upload"
                                               class="img-thumbnail"></th>
                        <td style="vertical-align: middle">
                            <div class="progress">
                                <div class="progress-bar progress-bar-success" role="progressbar" style="width: 32%">
                                    <span>32% Excited</span>
                                </div>
                                <div class="progress-bar progress-bar-danger" role="progressbar" style="width: 12%">
                                    <span>12% Unhappy</span>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th width="100px"><img src="https://placeholdit.imgix.net/~text?txtsize=33&w=150&h=150"
                                               alt="Upload"
                                               class="img-thumbnail"></th>
                        <td style="vertical-align: middle">
                            <div class="progress">
                                <div class="progress-bar progress-bar-success" role="progressbar" style="width: 32%">
                                    <span>32% Excited</span>
                                </div>
                                <div class="progress-bar progress-bar-danger" role="progressbar" style="width: 12%">
                                    <span>12% Unhappy</span>
                                </div>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>

        </div>

    </div>
</div>
