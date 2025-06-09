import React from 'react';
import { Link, usePage } from '@inertiajs/react';

export default function Home() {
  return (
    <div>
      Welcome Home from react without token in url{' '}
      <Link href={route('customize')}>
        go to customize
      </Link>
    </div>
  );
}
