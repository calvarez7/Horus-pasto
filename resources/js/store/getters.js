const getters = {
    fullName: (state) => `${state.user.name} ${state.user.apellido}`,
    UserEmail: (state) => `${state.user.email}`,
    id:(state) => `${state.user.id}`,
    can: (state) => (permission) => {
        if(permission === 'any') return true;
        if(state.user.permissions.find((permiso) => permiso.name === 'dev' )) return true;
        if(state.user.permissions.find((permiso) => permiso.name === permission )) return true;

        let roles = state.user.roles;
        let found = null;

        for (let i = 0; i < roles.length; i++) {

            found =  roles[i].permissions.find((perm) => perm.name == permission);

            if(found) return true;
        }

        return false;
    },
    avatar: (state) => state.user.avatar_url,
}
export default getters;
