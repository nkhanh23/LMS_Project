import os

from locust import HttpUser, task, between


class StackLearnVisitor(HttpUser):
    wait_time = between(1, 3)
    course_slug = os.getenv("STACKLEARN_COURSE_SLUG")

    @task(5)
    def home_page(self):
        self.client.get("/", name="Home")

    @task(4)
    def course_list(self):
        self.client.get("/khoa-hoc", name="Course list")

    @task(2)
    def search_courses(self):
        self.client.get(
            "/search/suggestions",
            params={"q": "laravel"},
            name="Search suggestions",
        )

    @task(1)
    def cart_page(self):
        self.client.get("/cart", name="Cart")

    @task(1)
    def course_detail(self):
        if self.course_slug:
            self.client.get(f"/chi-tiet/{self.course_slug}", name="Course detail")

    @task(1)
    def login_page(self):
        self.client.get("/login", name="Login")
