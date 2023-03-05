package errctrl

import "log"

func Ignore(fn func() error) {
	_ = fn()
}

func Warn[T any](v T, err error) T {
	if err != nil {
		log.Print(err)
	}
	return v
}
