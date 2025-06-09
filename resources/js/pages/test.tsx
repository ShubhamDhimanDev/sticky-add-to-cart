// import React from 'react'
// import { Link } from '@inertiajs/react';

// export default function test() {
//   return (
//     <div>Welcome Test from react<Link href={route('home')}>go to home</Link></div>
//   )
// }


import React from 'react'
import { Link } from '@inertiajs/react';

export default function test() {
  return (
    <div>Welcome Test from react without token in url<Link href={route('home')}>go to home</Link></div>
  )
}
