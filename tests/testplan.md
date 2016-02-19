#Test Plan

##Fixture Data
I have a db redstar-fixture.  I can configure the app to use redstar-fixture for
ordinary operation.  The test fixtures have been setup to copy the data from redstar-fixture
into redstar-test. This has proven to be reasonable method to provide good example data.
I can use the ordinary app to manipulate the various records and their relationships,
and then set appropriate constants in tests/Fixture/FixtureConstants to point to
the various records. I can then refer to said records by using these constants.
But be careful to ensure that the constants stay in sync with the redstar-fixture.

##Categories of Testing
There are several basic categories of testing that I'm interested in:

* Ordinary correct operation, such as CRUD operations.

	I will call every URL I can enumerate. I want to verify basic screen content.

* What happens with bad inputs ?  SQL injection?

	Can I trigger validation messages?

* After all of the above, what does code coverage look like?

* How do I know that routing works correctly?

	Do all possible routes go somewhere?

	Can some urls get past routing and invoke SkyNet?

* Run db test scenarios.

* Does the html output adhere to standards?

##Orders/OrderTransactions

Orders and OrderTransactions are two logically distinct classes in this app, which unfortunately appear
 glommed together to the ordinary user. So we have to tread very lightly around them.
 
An Order is a declaration of 'haves' and 'wants' by a particular trader.  For example, the trader
may _have_ dilithium crystals and _want_ quatloos. But it's the job of an OrderTransaction to record
the date and quantities. At anytime, the actual quantities of tradeables that are had and wanted,
are the sums of said amounts as recorded in the OrderTransactions.

This may seem like a lot of trouble, but please realize that an Order may be partially fulfilled, 
so we need some means of recording any number of 'OrderTransactions' that apply.  So we declare that 
Orders haveMany OrderTransactions and that OrderTransactions belongTo Orders. We can nest the routing 
as well, so that we can have urls such as /orders/3/order_transactions (to list all OrderTransactions 
for a particular Order.

This affects testing because the ordinary CRUD operations just don't apply with these two
tables.  For example, /orders/add will create a new Order record as well as the 1st OrderTransaction
for that record.  We cannot change the trader for an Order, nor can we change the have and want 
tradeables.  All we can do is wait for an order to be fulfilled, or cancel it. Attempting to delete or
modify any of this, makes everything needlessly complicated. 
