import React from 'react'

import DashboardSidebar from "./components/DashboardSidebar";

function Dashboard({users}) {
    return (
        <div>
            <h1>Dashboard</h1>
            <p>Welcome to Admin Dashboard!</p>
            <DashboardSidebar />
            <div>
                {users &&
                <ul>
                    { users.map((user) => {
                        const { id, name } = user
                        return (
                            <li key={`user-${id}`}>{name}: {id}</li>
                        )
                    })}
                </ul>
                }
            </div>
        </div>
    )
}

export async function getStaticProps() {
    const usersRes = await fetch(process.env.API_URI + '/api/users')
    const users = await usersRes.json()

    return {
        props: {
            users,
        },
        revalidate: 15,
    }
}

export default Dashboard