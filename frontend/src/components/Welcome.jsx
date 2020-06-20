import React from 'react'
import styles from './Welcome.module.css'

function Welcome() {
  return (
    <div data-test="welcome" className={styles.welcome}>
      <h1>School Management</h1>
      <p>We will be here soon</p>
    </div>
  )
}

export default Welcome
