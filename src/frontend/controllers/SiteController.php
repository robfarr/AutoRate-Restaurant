<?php
namespace frontend\controllers;

use app\models\Restaurant;
use app\models\Review;
use Yii;
use yii\helpers\Url;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\ImageForm;
use common\models\LoginForm;
use common\models\CognitiveInterface;
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

	    $model = new ImageForm();

        if(Yii::$app->request->isPost) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if($model->upload()) {

                $imageUrl = Url::home(true) . '/uploads/' . $model->imageFile->name;

            	$ci = new CognitiveInterface($imageUrl);

            	$review = new Review([
					'image'         => Url::home() . '/uploads/' . $model->imageFile->name,
	                'restaurant'    => 1,   // TODO
	                'user'          => Yii::$app->user->id,
	                'score'         => $ci->getPercentileScore(),
	                'emotion'       => $ci->getDominantEmotion()["emotion"],
	            ]);

                return;
            }
        }

        return $this->render('index', ['model' => $model, 'restaurants' => Restaurant::find()->all()]);
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

		    return $this->render('viewRestaurant', [
			    'model'         => $restaurant,
			    'reviews'       => $reviews,
		        'mostCommon'    => $restaurant->getMostFrequentEmotion(),
		    ]);

	    }else{
		    throw new BadRequestHttpException("Invalid restaurant id.");
	    }

    }

	public function actionTesting() {
		$imurl = "http://i.huffpost.com/gen/616696/thumbs/r-MIB-IMAGE-4-large570.jpg";
		$ci = new CognitiveInterface($imurl);
		return $this->render('test', [
			"imgurl" => $imurl,
			"test_data" => [
				'num faces' => $ci->getNumFaces(),
				'raw' => $ci->getEmotionValues(),
				'dominant' => $ci->getDominantEmotion(),
				'score' => $ci->getPercentileScore()
			]
		]
		);
	}

}
