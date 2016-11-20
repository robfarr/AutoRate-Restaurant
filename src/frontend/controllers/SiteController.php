<?php
namespace frontend\controllers;

use app\models\Restaurant;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

	/**
	 * Displays all ratings for a particular restaurant.
	 *
	 * @param $restaurant
	 * @return mixed
	 * @throws BadRequestHttpException
	 */
    public function actionViewRestaurant($restaurant) {

    	if(Restaurant::findOne($restaurant)) {

		    $restaurant = Restaurant::findOne($restaurant);
		    $reviews = $restaurant->reviews;

		    // Determine aggregate rankings.
		    $rankings = [
		    	'anger'     => 0,
		        'contempt'  => 0,
		        'disgust'   => 0,
		        'fear'      => 0,
		        'happiness' => 0,
		        'neutral'   => 0,
		        'sadness'   => 0,
		    ];
		    foreach($reviews as $r) {
		        $rankings['anger'] += $r->anger;
			    $rankings['contempt'] += $r->contempt;
			    $rankings['disgust'] += $r->disgust;
			    $rankings['fear'] += $r->fear;
			    $rankings['happiness'] += $r->happiness;
			    $rankings['neutral'] += $r->neutral;
			    $rankings['sadness'] += $r->sadness;
		    }
		    
		    $mostCommon = array_keys($rankings, max($rankings))[0];
		    $verbs = [
		    	'anger'     =>  'angry',
		        'contempt'  =>  'contempt',
		        'disgust'   =>  'disgusted',
		        'fear'      =>  'fearful',
		        'happiness' =>  'happy',
		        'neutral'   =>  'neutral',
		        'sadness'   =>  'sad',
		    ];
		    $mostCommon = $verbs[$mostCommon];

		    foreach($rankings as $emotion=>$value) {
		    	$rankings[$emotion] = $value / count($reviews);
		    }

		    return $this->render('viewRestaurant', [
			    'model'         => $restaurant,
			    'reviews'       => $reviews,
		        'mostCommon'    => $mostCommon,
		        'aggregate'     => $rankings,
		    ]);

	    }else{
		    throw new BadRequestHttpException("Invalid restaurant id.");
	    }

    }

}
