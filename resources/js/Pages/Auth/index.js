export const validateUserAuth = async (inertia, query, callback = false) => {
    if(
        Number(inertia.page.props.user.id) === Number(query.user_id) &&
        Number(inertia.page.props.user.empresa_id) === Number(query.empresa_id)
    ){
        callback && callback();
        console.log('User Auth');
    }
    else
    {
        console.log(inertia.page.props);
        inertia.post(route('logout'));
    }
};
