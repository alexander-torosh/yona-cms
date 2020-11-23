import React from 'react'

import HomePage from "./components/HomePage";

function Index({apiData}) {
    return <HomePage apiData={apiData} />
}

export async function getStaticProps() {
    const apiDataRes = await fetch(process.env.API_URI + '/api')
    const apiData = await apiDataRes.json()

    return {
        props: {
            apiData,
        },
        revalidate: 15,
    }
}

export default Index