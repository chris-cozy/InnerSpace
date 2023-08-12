-- Create the users table to store user information
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    bio TEXT,
    profile_pic VARCHAR(255),
    banner_pic VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Create the posts table to store user posts
CREATE TABLE posts (
    post_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    content_type ENUM('text', 'photo', 'video') DEFAULT 'text',
    media_path VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    likes_count INT DEFAULT 0
    FOREIGN KEY (user_id) REFERENCES users (user_id)
);

-- Create the follows table to store follow relationships
CREATE TABLE follows (
    follower_id INT NOT NULL,
    following_id INT NOT NULL,
    PRIMARY KEY (follower_id, following_id),
    FOREIGN KEY (follower_id) REFERENCES users (user_id),
    FOREIGN KEY (following_id) REFERENCES users (user_id)
);

-- Create the conversations table to store conversation details
CREATE TABLE conversations (
    conversation_id INT AUTO_INCREMENT PRIMARY KEY,
    user1_id INT NOT NULL,
    user2_id INT NOT NULL,
    UNIQUE KEY unique_users (user1_id, user2_id),
    FOREIGN KEY (user1_id) REFERENCES users (user_id),
    FOREIGN KEY (user2_id) REFERENCES users (user_id)
);

-- Create the messages table to store messages in conversations
CREATE TABLE messages (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    conversation_id INT NOT NULL,
    sender_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (conversation_id) REFERENCES conversations (conversation_id),
    FOREIGN KEY (sender_id) REFERENCES users (user_id)
);

-- Create the likes table to store likes on posts
CREATE TABLE likes (
    like_id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    FOREIGN KEY (post_id) REFERENCES posts (post_id),
    FOREIGN KEY (user_id) REFERENCES users (user_id)
);

-- Create the comments table to store comments on posts
CREATE TABLE comments (
    comment_id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts (post_id),
    FOREIGN KEY (user_id) REFERENCES users (user_id)
);
