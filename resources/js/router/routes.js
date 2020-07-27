import QuestionsPage from '../pages/QuestionsPage.vue'
import QuestionPage from '../pages/QuestionPage.vue'
import MyPostsPage from '../pages/MyPostsPage.vue'

const routes = [
    {
        path: '/',
        component: QuestionsPage,
        name: 'home',
    },
    {
        path: '/questions',
        component: QuestionsPage,
        name: 'questions',
    },
    {
        path: '/my-posts',
        component: MyPostsPage,
        name: 'my-posts',
    },
    {
        path: '/questions/:slug',
        component: QuestionPage,
        name: 'questions.show',
    }
];

export default routes
