INSERT INTO UserClass (email_address, name, password, phone_number, role, is_admin)
VALUES
   ('alice@example.com', 'Alice Wonderland', 'pass123', '111-222-3333', 'Event_Creator', FALSE),
   ('bob@example.com', 'Bob Builder', 'pass456', '444-555-6666', 'Event_Manager', FALSE),
   ('carol@example.com', 'Carol Singer', 'pass789', '777-888-9999', 'Event_Creator', TRUE),
   ('dave@example.com', 'Dave Dancer', 'passABC', '999-000-1111', 'Event_Manager', FALSE),
   ('emma@example.com', 'Emma Explorer', 'passDEF', '222-333-4444', 'Event_Creator', FALSE);


INSERT INTO Event_ (name, location, description, start_timestamp, end_timestamp, creator_id)
VALUES
   ('Concert in the Park', 'City Park', 'Enjoy live music outdoors.', '2023-11-01 18:00:00', '2023-11-01 22:00:00', 1),
   ('Tech Conference', 'Convention Center', 'Explore the latest in technology.', '2023-11-10 09:00:00', '2023-11-11 18:00:00', 2),
   ('Art Exhibition', 'Art Gallery', 'A showcase of modern art.', '2023-11-05 12:00:00', '2023-11-05 18:00:00', 3),
   ('Dance Workshop', 'Community Center', 'Learn different dance styles.', '2023-11-15 14:00:00', '2023-11-15 17:00:00', 4),
   ('Nature Walk', 'Nature Reserve', 'Explore the beauty of nature.', '2023-11-20 10:00:00', '2023-11-20 12:00:00', 5);


INSERT INTO Comment_ (text, event_id, author_id)
VALUES
   ('Great concert!', 1, 2),
   ('Interesting keynote speakers.', 2, 3),
   ('Wonderful art pieces.', 3, 4),
   ('Loved the dance workshop!', 4, 5),
   ('Amazing scenery during the nature walk.', 5, 1);


INSERT INTO Rating (rating, event_id, author_id)
VALUES
   (5, 1, 3),
   (4, 2, 4),
   (5, 3, 1),
   (4, 4, 2),
   (5, 5, 5);


INSERT INTO Report (Type, comment_id, author_id)
VALUES
   ('Spam', 1, 4),
   ('Inappropriate', 2, 5),
   ('Abuse', 3, 1),
   ('Spam', 4, 2),
   ('Inappropriate', 5, 3);


INSERT INTO TicketType (name, stock, description, person_buying_limit, start_timestamp, end_timestamp, price, event_id)
VALUES
   ('VIP Pass', 50, 'Access to exclusive areas.', 5, '2023-11-01 18:00:00', '2023-11-01 22:00:00', 100.00, 1),
   ('General Admission', 100, 'Standard entry ticket.', 10, '2023-11-10 09:00:00', '2023-11-11 18:00:00', 50.00, 2),
   ('Art Lover Package', 30, 'Includes catalog and VIP access.', 3, '2023-11-05 12:00:00', '2023-11-05 18:00:00', 75.00, 3),
   ('Dance Enthusiast Ticket', 20, 'Participate in dance activities.', 2, '2023-11-15 14:00:00', '2023-11-15 17:00:00', 30.00, 4),
   ('Nature Explorer Pass', 80, 'Guided tour through the reserve.', 15, '2023-11-20 10:00:00', '2023-11-20 12:00:00', 10.00, 5);


INSERT INTO TicketOrder (timestamp, promo_code, buyer_id)
VALUES
   ('2023-10-30 08:00:00', 'EARLYBIRD', 1),
   ('2023-11-02 15:30:00', 'TECH20', 2),
   ('2023-11-07 11:45:00', NULL, 3),
   ('2023-11-12 16:20:00', 'DANCEVIP', 4),
   ('2023-11-18 09:30:00', 'NATURE10', 5);

INSERT INTO TicketInstance (ticket_type_id, order_id)
VALUES
   (1, 1),
   (2, 2),
   (3, 3),
   (4, 4),
   (5, 5);

INSERT INTO Tag (name)
VALUES
   ('Music'),
   ('Technology'),
   ('Art'),
   ('Dance'),
   ('Nature');

INSERT INTO TagEvent (event_id, tag_id)
VALUES
   (1, 1),
   (2, 2),
   (3, 3),
   (4, 4),
   (5, 5);


INSERT INTO FAQ (question, answer)
VALUES
   ('How can I buy tickets?', 'You can purchase tickets online through our website.'),
   ('What payment methods are accepted?', 'We accept various payment methods, including credit/debit cards and online payment systems.'),
   ('How can I contact customer support?', 'You can reach our customer support team through the "Contact Us" section on our website.');